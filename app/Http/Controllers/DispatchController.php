<?php

namespace App\Http\Controllers;

use App\Http\Requests\DispatchRequest;
use Illuminate\Http\Request;
use App\Models\Dispatch;
use App\Models\Distributor;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dispatches = Dispatch::with('distributor', 'product')->orderBy('id', 'DESC')->whereNull('deleted_at')->get();

        return view('dispatch.index', ['dispatches' => $dispatches]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // === fetch distributor_id
        $distributors = Distributor::whereNull('deleted_at')->orderBy('id', 'asc')->get(['id', 'distributor_name']);

        // ===== Fetch Product
        $products = Product::whereNull('deleted_at')->orderBy('id', 'asc')->get(['id', 'name']);
        return view('dispatch.create',[
            'distributors' => $distributors,
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DispatchRequest $request)
    {
        $request->validated();
        try {
            // Convert the incoming QR code serial numbers into a JSON array
            $incomingQrCodes = json_encode($request->external_qr_code_serial_number);

            // Check if the external_qr_code_serial_number is already assigned
            $existingDispatch = Dispatch::where('distributor_id', $request->distributor_id)
                ->where('product_id', $request->product_id)
                ->get();

            foreach ($existingDispatch as $dispatch) {
                $existingQrCodes = json_decode($dispatch->external_qr_code_serial_number, true);

                // Check for intersection between incoming QR codes and existing QR codes
                if (array_intersect($existingQrCodes, json_decode($incomingQrCodes, true))) {
                    return redirect()->back()->with('error', 'The specified QR code serial number is already assigned.');
                }
            }

            // Proceed with creating a new dispatch
            $dispatch = new Dispatch();

            $dispatch->user_id = Auth::user()->id;
            $dispatch->distributor_id = $request->distributor_id;
            $dispatch->product_id = $request->product_id;
            $dispatch->quantity = $request->quantity;
            $dispatch->external_qr_code_serial_number = $incomingQrCodes;
            $dispatch->remarks = $request->remarks;
            $dispatch->dispatched_at = date('Y-m-d H:i:s', strtotime($request->dispatched_at));
            $dispatch->inserted_at = Carbon::now();
            $dispatch->inserted_by = Auth::user()->id;
            $dispatch->save();

            // Generate dispatch code including (year, 6-digit serial number)
            $dispatch_code = date('Y', strtotime($dispatch->inserted_at)).'-'.sprintf('%06d', $dispatch->id);

            // Update dispatch code
            Dispatch::where('id', $dispatch->id)->update([
                'dispatch_code' => $dispatch_code
            ]);

            // Update available_quantity in qr_codes based on the dispatched quantity
            $qrCode = DB::table('qr_codes')->where('product_id', $dispatch->product_id)->first();

            if ($qrCode) {
                if (isset($qrCode->avilable_quantity)) {
                    // Calculate new available_quantity
                    $currentAvailableQuantity = $qrCode->avilable_quantity;
                    $newAvailableQuantity = $currentAvailableQuantity - $dispatch->quantity;

                    // Check if the new available quantity is not negative
                    if ($newAvailableQuantity < 0) {
                        return redirect()->back()->with('error', 'Insufficient quantity available.');
                    }

                    // Update available_quantity in qr_codes table
                    DB::table('qr_codes')
                        ->where('product_id', $dispatch->product_id)
                        ->update(['avilable_quantity' => $newAvailableQuantity]);

                } else {
                    return redirect()->back()->with('error', 'Total quantity is not defined for the product.');
                }
            } else {
                return redirect()->back()->with('error', 'No QR code record found for the specified product.');
            }

            return redirect()->route('dispatch.index')->with('message', 'Your record has been successfully created.');

        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dispatch = Dispatch::findOrFail($id);

        // === fetch distributor_id
        $distributors = Distributor::whereNull('deleted_at')->orderBy('id', 'asc')->get(['id', 'distributor_name']);

        // ===== Fetch Product
        $products = Product::whereNull('deleted_at')->orderBy('id', 'asc')->get(['id', 'name']);

        // Decode the existing external QR codes if they exist
        $externalQrCodes = json_decode($dispatch->external_qr_code_serial_number, true) ?? [];

        return view('dispatch.edit', [
            'dispatch' => $dispatch,
            'distributors' => $distributors,
            'products' => $products,
            'externalQrCodes' => $externalQrCodes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DispatchRequest $request, string $id)
    {
        $request->validated();
        try {
            $dispatch = Dispatch::findOrFail($id);

            // Get the old quantity before updating
            $oldQuantity = $dispatch->quantity;

            // Check if the external_qr_code_serial_number is already assigned
            $incomingQrCodes = json_encode($request->external_qr_code_serial_number);
            $existingDispatches = Dispatch::where('distributor_id', $request->distributor_id)
                ->where('product_id', $request->product_id)
                ->where('id', '!=', $dispatch->id) // Exclude the current dispatch being updated
                ->get();

            foreach ($existingDispatches as $existingDispatch) {
                $existingQrCodes = json_decode($existingDispatch->external_qr_code_serial_number, true);

                // Check for intersection between incoming QR codes and existing QR codes
                if (array_intersect($existingQrCodes, json_decode($incomingQrCodes, true))) {
                    return redirect()->back()->with('error', 'The specified QR code serial number is already assigned.');
                }
            }

            // Update the dispatch record with new data
            $dispatch->user_id = Auth::user()->id;
            $dispatch->distributor_id = $request->distributor_id;
            $dispatch->product_id = $request->product_id;
            $dispatch->quantity = $request->quantity;
            $dispatch->external_qr_code_serial_number = $incomingQrCodes;
            $dispatch->remarks = $request->remarks;
            $dispatch->dispatched_at = date('Y-m-d H:i:s', strtotime($request->dispatched_at));
            $dispatch->modified_at = Carbon::now();
            $dispatch->modified_by = Auth::user()->id;
            $dispatch->save();

            // Update available_quantity in qr_codes based on the dispatched quantity
            $qrCode = DB::table('qr_codes')->where('product_id', $dispatch->product_id)->first();

            if ($qrCode) {
                if (isset($qrCode->avilable_quantity)) {
                    // Calculate new available_quantity
                    $currentAvailableQuantity = $qrCode->avilable_quantity;

                    // Calculate the new available quantity based on the old and new quantities
                    $newAvailableQuantity = $currentAvailableQuantity + $oldQuantity - $dispatch->quantity;

                    // Check if the new available quantity is not negative
                    if ($newAvailableQuantity < 0) {
                        return redirect()->back()->with('error', 'Insufficient quantity available.');
                    }

                    // Update available_quantity in qr_codes table
                    DB::table('qr_codes')
                        ->where('product_id', $dispatch->product_id)
                        ->update(['avilable_quantity' => $newAvailableQuantity]);
                } else {
                    return redirect()->back()->with('error', 'Total quantity is not defined for the product.');
                }
            } else {
                return redirect()->back()->with('error', 'No QR code record found for the specified product.');
            }

            return redirect()->route('dispatch.index')->with('message', 'Your record has been successfully updated.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data['deleted_by'] =  Auth::user()->id;
        $data['deleted_at'] =  Carbon::now();
        try {
            $dispatch = Dispatch::findOrFail($id);
            $dispatch->update($data);

            return redirect()->route('dispatch.index')->with('message','Your record has been successfully deleted.');
        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong - '.$ex->getMessage());
        }
    }
}
