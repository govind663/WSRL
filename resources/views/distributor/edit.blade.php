@extends('layouts.master')

@section('title')
Bhairaav | Edit Distributor
@endsection

@push('styles')
@endpush

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Edit Distributor</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('distributor.index') }}">Manage Distributor</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Edit Distributor
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>


        <form method="POST" action="{{ route('distributor.update', $distributor->id) }}" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <input type="text" id="id" name="id" hidden  value="{{ $distributor->id }}">

            <div class="pd-20 card-box mb-30">
                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Distributor Code : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="distributor_code" id="distributor_code" class="form-control @error('distributor_code') is-invalid @enderror" value="{{ $distributor->distributor_code }}" placeholder="Enter Distributor Code.">
                        @error('distributor_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Distributor GSTIN : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="distributor_gstin" id="distributor_gstin" class="form-control @error('distributor_gstin') is-invalid @enderror" value="{{ $distributor->distributor_gstin }}" placeholder="Enter Distributor GSTIN.">
                        @error('distributor_gstin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Distributor Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="distributor_name" id="distributor_name" class="form-control @error('distributor_name') is-invalid @enderror" value="{{ $distributor->distributor_name }}" placeholder="Enter Distributor Name.">
                        @error('distributor_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Distributor POS : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="distributor_pos" id="distributor_pos" class="form-control @error('distributor_pos') is-invalid @enderror" value="{{ $distributor->distributor_pos }}" placeholder="Enter Distributor POS.">
                        @error('distributor_pos')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Email Id : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ $distributor->email }}" placeholder="Enter Email Id.">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Mobile Number : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" maxlength="10" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ $distributor->contact_person }}" placeholder="Enter Mobile Number.">
                        @error('contact_person')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-1"><b>State : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-2 col-md-2">
                        <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ $distributor->state }}" placeholder="Enter State.">
                        @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-1"><b>City : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-2 col-md-2">
                        <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ $distributor->city }}" placeholder="Enter City.">
                        @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Postal Code : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" maxlength="6" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ $distributor->postal_code }}" placeholder="Enter Pin Code.">
                        @error('postal_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Country : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ $distributor->country }}" placeholder="Enter Country.">
                        @error('country')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Division : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="division" id="division" class="form-control @error('division') is-invalid @enderror" value="{{ $distributor->division }}" placeholder="Enter Division.">
                        @error('division')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Distributor Address (1) : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <textarea type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ $distributor->address }}" placeholder="Enter Distributor Address.">{{ $distributor->address }}</textarea>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <label class="col-sm-2"><b>Distributor Address (2) : </b></label>
                    <div class="col-sm-4 col-md-4">
                        <textarea type="text" name="other_address" id="other_address" class="form-control @error('other_address') is-invalid @enderror" value="{{ $distributor->other_address }}" placeholder="Enter Distributor other_Address.">{{ $distributor->other_address }}</textarea>
                        @error('other_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <label class="col-md-3"></label>
                    <div class="col-md-9" style="display: flex; justify-content: flex-end;">
                        <a href="{{ route('distributor.index') }}" class="btn btn-danger">Cancel</a>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>

        </form>

    </div>

    <!-- Footer Start -->
    <x-footer />
    <!-- Footer Start -->
</div>
@endsection

@push('scripts')
@endpush
