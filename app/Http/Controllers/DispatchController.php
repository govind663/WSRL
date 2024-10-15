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
            $dispatch = new Dispatch();

            $dispatch->user_id = Auth::user()->id;
            $dispatch->distributor_id = $request->distributor_id;
            $dispatch->product_id = $request->product_id;
            $dispatch->quantity = $request->quantity;
            $dispatch->external_qr_code_serial_number = json_encode($request->external_qr_code_serial_number);
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
                if (isset($qrCode->quantity)) {
                    // Calculate new available_quantity
                    $newAvailableQuantity = $qrCode->quantity - $dispatch->quantity;

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

            // Update the dispatch record with new data
            $dispatch->user_id = Auth::user()->id;
            $dispatch->distributor_id = $request->distributor_id;
            $dispatch->product_id = $request->product_id;
            $dispatch->quantity = $request->quantity;
            $dispatch->external_qr_code_serial_number = json_encode($request->external_qr_code_serial_number);
            $dispatch->remarks = $request->remarks;
            $dispatch->dispatched_at = date('Y-m-d H:i:s', strtotime($request->dispatched_at));
            $dispatch->modified_at = Carbon::now();
            $dispatch->modified_by = Auth::user()->id;
            $dispatch->save();

            // Update available_quantity in qr_codes based on the dispatched quantity
            $qrCode = DB::table('qr_codes')->where('product_id', $dispatch->product_id)->first();

            if ($qrCode) {
                if (isset($qrCode->quantity)) {
                    // Calculate new available_quantity
                    $newAvailableQuantity = $qrCode->quantity - $dispatch->quantity;

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
