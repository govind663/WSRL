@extends('layouts.master')

@section('title')
Bhairaav | Edit Dispatch
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
                        <h4>Edit Dispatch</h4>
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
                                Edit Dispatch
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>


        <form method="POST" action="{{ route('dispatch.update', $dispatch->id) }}" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <input type="text" id="id" name="id" hidden  value="{{ $dispatch->id }}">

            <div class="pd-20 card-box mb-30">
                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Distributor Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select class="custom-select2 form-control @error('distributor_id') is-invalid @enderror" name="distributor_id" id="distributor_id" style="width: 100%; height: 38px;">
                            <option value="" >Select Distributor Name</option>
                            <optgroup label="">
                                @foreach ($distributors as $value)
                                    <option value="{{ $value->id }}" {{ $dispatch->distributor_id == $value->id ? 'selected' : '' }}>{{ $value->distributor_name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('distributor_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Product Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select class="custom-select2 form-control @error('product_id') is-invalid @enderror" name="product_id" id="product_id" style="width: 100%; height: 38px;">
                            <option value=" " >Select Product Name</option>
                            <optgroup label="">
                                @foreach ($products as $value)
                                    <option value="{{ $value->id }}" {{ $dispatch->product_id == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('product_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Quantity : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ $dispatch->quantity }}" placeholder="Enter Quantity.">
                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Dispatch Date : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="dispatched_at" id="dispatched_at" class="form-control date-picker @error('dispatched_at') is-invalid @enderror" value="{{ $dispatch->dispatched_at }}" placeholder="Enter Dispatch Date.">
                        @error('dispatched_at')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>External QrCode Serial Number : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select class="custom-select2 form-control @error('external_qr_code_serial_number') is-invalid @enderror" multiple="multiple" name="external_qr_code_serial_number[]" id="external_qr_code_serial_number" style="width: 100%; height: 38px;">
                            <optgroup label="External QrCode Serial Number">
                                @foreach ($externalQrCodes as $qrCode)
                                    <option value="{{ $qrCode }}"
                                    {{ is_array(json_decode($dispatch->external_qr_code_serial_number, true)) && in_array($qrCode, json_decode($dispatch->external_qr_code_serial_number, true)) ? 'selected' : '' }}>
                                        {{ $qrCode }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('external_qr_code_serial_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-4"><b>Remarks : </b></label>
                    <div class="col-sm-12 col-md-12">
                        <textarea type="text" name="remarks" id="remarks" class="textarea_editor form-control @error('remarks') is-invalid @enderror" value="{!! $dispatch->remarks !!}" placeholder="Enter Remarks.">{!! $dispatch->remarks !!}</textarea>
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
<script>
    $(document).ready(function () {
        // Variable to store the previously selected value
        var previousValues = @json($externalQrCodes); // Get the previously selected QR codes from the server

        $('#product_id').on('change', function () {
            var product_id = this.value;
            $("#external_qr_code_serial_number").html(''); // Clear the dropdown

            $.ajax({
                url: "{{ route('qrcode.fetch-avilable-quantity') }}",
                type: "POST",
                data: {
                    productId: product_id,
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'json',
                success: function (result) {
                    // Set the available quantity
                    $("#avilable_product_quantity").val(result.available_quantity);

                    // Populate the external QR code dropdown
                    if (result.external_qr_codes.length > 0) {
                        $.each(result.external_qr_codes, function(index, value) {
                            $("#external_qr_code_serial_number").append(
                                '<option value="' + value + '">' + value + '</option>'
                            );
                        });
                    } else {
                        $("#external_qr_code_serial_number").append(
                            '<option value="">No External QR Codes Available</option>'
                        );
                    }

                    // Re-select the previous values if they still exist
                    $("#external_qr_code_serial_number").val(previousValues);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching available quantity:", error);
                }
            });
        });
    });
</script>

@endpush
