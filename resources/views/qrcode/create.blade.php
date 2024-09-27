@extends('layouts.master')

@section('title')
WSRL | Generate QR Code
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
                        <h4>Generate QR Code</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('qrcode.index') }}">Manage QR Code</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Generate QR Code
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>


        <form method="POST" action="{{ route('qrcode.store') }}" class="form-horizontal" enctype="multipart/form-data">
            @csrf

            <div class="pd-20 card-box mb-30">
                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Product Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select name="product_id" id="product_id" class="form-control custom-select2 @error('product_id') is-invalid @enderror">
                            <option value="">Select Product Name</option>
                            <optgroup label="Product Name">
                                @foreach ($products as $value => $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('product_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Avilable Product Quantity : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input readonly type="text" name="avilable_product_quantity" id="avilable_product_quantity" class="form-control @error('avilable_product_quantity') is-invalid @enderror" value="{{ old('avilable_product_quantity') }}">
                        @error('avilable_product_quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Product Quantity : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="current_product_quantity" id="current_product_quantity" class="form-control @error('current_product_quantity') is-invalid @enderror" value="{{ old('current_product_quantity') }}" placeholder="Enter Product Quantity.">
                        @error('current_product_quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Quantity to Generate QR Codes : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" placeholder="Enter Quantity to Generate QR Codes.">
                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label class="col-md-3"></label>
                    <div class="col-md-9" style="display: flex; justify-content: flex-end;">
                        <a href="{{ route('qrcode.index') }}" class="btn btn-danger">Cancel</a>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success">Generate QR Codes</button>
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
<script>
    $(document).ready(function () {
        $('#product_id').on('change', function () {
            var product_id = this.value;
            $("#avilable_product_quantity").html('');
            $.ajax({
                url: "{{ route('qrcode.fetch-avilable-quantity') }}",
                type: "POST",
                data: {
                    productId: product_id,
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'json',
                success: function (result) {
                    // display in input value in response available_quantity return
                    $("#avilable_product_quantity").val(result.available_quantity);
                }
            });
        });
    });
</script>
@endpush
