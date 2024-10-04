@extends('layouts.master')

@section('title')
Bhairaav | Add Dispatch
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
                        <h4>Add Dispatch</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('dispatch.index') }}">Manage Dispatch</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Add Dispatch
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>


        <form method="POST" action="{{ route('dispatch.store') }}" class="form-horizontal" enctype="multipart/form-data">
            @csrf

            <div class="pd-20 card-box mb-30">
                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Dispatch Code : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="dispatch_code" id="dispatch_code" class="form-control @error('dispatch_code') is-invalid @enderror" value="{{ old('dispatch_code') }}" placeholder="Enter Dispatch Code.">
                        @error('dispatch_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Distributor Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select class="custom-select2 form-control @error('distributor_id') is-invalid @enderror" name="distributor_id" id="distributor_id" style="width: 100%; height: 38px;">
                            <option value=" " >Select Distributor Name</option>
                            <optgroup label="Category">
                                @foreach ($distributors as $value)
                                    <option value="{{ $value->id }}" {{ old('distributor_id') == $value->id ? 'selected' : '' }}>{{ $value->distributor_name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('distributor_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Product Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select class="custom-select2 form-control @error('product_id') is-invalid @enderror" name="product_id" id="product_id" style="width: 100%; height: 38px;">
                            <option value=" " >Select Product Name</option>
                            <optgroup label="Category">
                                @foreach ($products as $value)
                                    <option value="{{ $value->id }}" {{ old('product_id') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('product_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Quantity : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" placeholder="Enter Quantity.">
                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Dispatch Date : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="dispatched_at" id="dispatched_at" class="form-control date-picker @error('dispatched_at') is-invalid @enderror" value="{{ old('dispatched_at') }}" placeholder="Enter Dispatch Date.">
                        @error('dispatched_at')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-4"><b>Remarks : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-12 col-md-12">
                        <textarea type="text" name="remarks" id="remarks" class="textarea_editor form-control @error('remarks') is-invalid @enderror" value="{!! old('remarks') !!}" placeholder="Enter Remarks.">{!! old('remarks') !!}</textarea>
                        @error('remarks')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label class="col-md-3"></label>
                    <div class="col-md-9" style="display: flex; justify-content: flex-end;">
                        <a href="{{ route('dispatch.index') }}" class="btn btn-danger">Cancel</a>&nbsp;&nbsp;
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
