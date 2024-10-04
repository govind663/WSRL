<?php

namespace App\Http\Controllers;

use App\Http\Requests\DispatchRequest;
use Illuminate\Http\Request;
use App\Models\Dispatch;
use App\Models\Distributor;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

            $dispatch->dispatch_code = $request->dispatch_code;
            $dispatch->user_id = Auth::user()->id;
            $dispatch->distributor_id = $request->distributor_id;
            $dispatch->product_id = $request->product_id;
            $dispatch->quantity = $request->quantity;
            $dispatch->remarks = $request->remarks;
            $dispatch->dispatched_at = date('Y-m-d H:i:s', strtotime($request->dispatched_at));
            $dispatch->inserted_at = Carbon::now();
            $dispatch->inserted_by = Auth::user()->id;
            $dispatch->save();

            return redirect()->route('dispatch.index')->with('message','Your record has been successfully created.');

        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong  - '.$ex->getMessage());
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
        return view('dispatch.edit', [
            'dispatch' => $dispatch,
            'distributors' => $distributors,
            'products' => $products
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

            $dispatch->dispatch_code = $request->dispatch_code;
            $dispatch->user_id = Auth::user()->id;
            $dispatch->distributor_id = $request->distributor_id;
            $dispatch->product_id = $request->product_id;
            $dispatch->quantity = $request->quantity;
            $dispatch->remarks = $request->remarks;
            $dispatch->dispatched_at = date('Y-m-d H:i:s', strtotime($request->dispatched_at));
            $dispatch->modified_at = Carbon::now();
            $dispatch->modified_by = Auth::user()->id;
            $dispatch->save();

            return redirect()->route('dispatch.index')->with('message','Your record has been successfully updated.');
        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong  - '.$ex->getMessage());
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