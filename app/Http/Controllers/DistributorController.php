<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistributorRequest;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distributors = Distributor::orderBy('id', 'DESC')->whereNull('deleted_at')->get();
        return view('distributor.index', ['distributors' => $distributors]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('distributor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DistributorRequest $request)
    {
        $request->validated();
        // dd($request);
        try {

            $distributor = new Distributor();

            $distributor->distributor_gstin = $request->distributor_gstin;
            $distributor->distributor_name = $request->distributor_name;
            $distributor->distributor_pos = $request->distributor_pos;
            $distributor->contact_person = $request->contact_person;
            $distributor->email = $request->email;
            $distributor->address = $request->address;
            $distributor->other_address = $request->other_address;
            $distributor->division = $request->division;
            $distributor->city = $request->city;
            $distributor->state = $request->state;
            $distributor->postal_code = $request->postal_code;
            $distributor->country = $request->country;
            $distributor->inserted_at = Carbon::now();
            $distributor->inserted_by = Auth::user()->id;
            $distributor->save();

            // === generate distrbuted code include (year, 6-digit serial number)
            $distributor_code = date('Y', strtotime($distributor->inserted_at)).'-'.sprintf('%06d', $distributor->id);
            // ==== Update
            Distributor::where('id', $distributor->id)->update([
                'distributor_code' => $distributor_code
            ]);

            return redirect()->route('distributor.index')->with('message','Your record has been successfully created.');

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
        $distributor = Distributor::findOrFail($id);

        return view('distributor.edit', ['distributor' => $distributor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DistributorRequest $request, string $id)
    {
        $request->validated();
        try {

            $distributor = Distributor::findOrFail($id);

            $distributor->distributor_gstin = $request->distributor_gstin;
            $distributor->distributor_name = $request->distributor_name;
            $distributor->distributor_pos = $request->distributor_pos;
            $distributor->contact_person = $request->contact_person;
            $distributor->email = $request->email;
            $distributor->address = $request->address;
            $distributor->other_address = $request->other_address;
            $distributor->division = $request->division;
            $distributor->city = $request->city;
            $distributor->state = $request->state;
            $distributor->postal_code = $request->postal_code;
            $distributor->country = $request->country;
            $distributor->modified_at = Carbon::now();
            $distributor->modified_by = Auth::user()->id;
            $distributor->save();

            return redirect()->route('distributor.index')->with('message','Your record has been successfully updated.');

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
            $distributor = Distributor::findOrFail($id);
            $distributor->update($data);

            return redirect()->route('distributor.index')->with('message','Your record has been successfully deleted.');
        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong - '.$ex->getMessage());
        }
    }
}
