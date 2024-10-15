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
                    <label class="col-sm-2"><b>Distributor Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select class="custom-select2 form-control @error('distributor_id') is-invalid @enderror" name="distributor_id" id="distributor_id" style="width: 100%; height: 38px;">
                            <option value=" " >Select Distributor Name</option>
                            <optgroup label="">
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

                    <label class="col-sm-2"><b>Product Name : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <select class="custom-select2 form-control @error('product_id') is-invalid @enderror" name="product_id" id="product_id" style="width: 100%; height: 38px;">
                            <option value=" " >Select Product Name</option>
                            <optgroup label="">
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
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>Quantity : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" placeholder="Enter Quantity.">
                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Dispatch Date : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="dispatched_at" id="dispatched_at" class="form-control date-picker @error('dispatched_at') is-invalid @enderror" value="{{ date('d-m-Y') }}" placeholder="Enter Dispatch Date.">
                        @error('dispatched_at')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2"><b>External QR Code Serial Number : <span class="text-danger">*</span></b></label>
                    <div class="col-sm-6 col-md-6">
                        <select class="custom-select2 form-control @error('external_qr_code_serial_number') is-invalid @enderror" multiple="multiple" name="external_qr_code_serial_number[]" id="external_qr_code_serial_number" style="width: 100%; height: 38px;" data-placeholder="Select External QR Code Serial Number" disabled>
                            <optgroup label="External QR Code Serial Number">
                            </optgroup>
                        </select>
                        @error('external_qr_code_serial_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label class="col-sm-2"><b>Selected External QR Code Serial Number : </b></label>
                    <div class="col-sm-2 col-md-2">
                        <input type="text" name="selected_count" id="selected-count" class="form-control" value="0" readonly>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-4"><b>Remarks : </b></label>
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
<script>
    $(document).ready(function () {
        // Initialize Toastr options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000", // Duration for which the toast will be visible
            "extendedTimeOut": "1000", // Duration after hover before hiding
        };

        let warningShown = false; // Flag to track if warning has been shown
        let previousSelection = []; // Variable to hold the last valid selection

        // Initialize Select2 for the external QR code serial number
        $('#external_qr_code_serial_number').select2({
            placeholder: "Select External QR Code Serial Number",
            allowClear: true // Allow clearing the selection
        });

        $('#product_id').on('change', function () {
            var product_id = this.value;
            $("#external_qr_code_serial_number").val(null).trigger('change'); // Clear previous selection
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

                    // Enable the dropdown after fetching data
                    $("#external_qr_code_serial_number").prop('disabled', false);
                    // Trigger the change event to update Select2
                    $("#external_qr_code_serial_number").trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching available quantity:", error);
                }
            });
        });

        // Function to update selected count display
        function updateSelectedCount() {
            const selectedOptionsCount = $('#external_qr_code_serial_number').val()?.length || 0;
            console.log("Selected Options Count:", selectedOptionsCount); // Debug log
            $('#selected-count').val(selectedOptionsCount); // Update the count display in the input field
        }

        // Monitor quantity input
        $('#quantity').on('input', function () {
            const enteredQuantity = parseInt($(this).val());
            const selectedOptions = $('#external_qr_code_serial_number').val() || [];

            // Deselect options if selected exceeds entered quantity
            if (selectedOptions.length > enteredQuantity) {
                // Deselect the excess options
                $('#external_qr_code_serial_number').val(selectedOptions.slice(0, enteredQuantity));
                $("#external_qr_code_serial_number").trigger('change');
            }

            // Disable the dropdown if entered quantity is reached
            if (enteredQuantity > 0) {
                $('#external_qr_code_serial_number').prop('disabled', false);
            } else {
                $('#external_qr_code_serial_number').prop('disabled', true);
            }

            // Reset the warning flag when user changes the quantity
            warningShown = false;
            updateSelectedCount(); // Update count display after quantity change
        });

        // Show toast message when trying to select more options than entered quantity
        $('#external_qr_code_serial_number').on('change', function() {
            const enteredQuantity = parseInt($('#quantity').val());
            const selectedOptions = $(this).val() || [];

            // Check if the selection exceeds the limit
            if (selectedOptions.length > enteredQuantity) {
                // Show warning only if it hasn't been shown already
                if (!warningShown) {
                    toastr.warning('You can only select up to ' + enteredQuantity + ' External QR Code Serial Numbers.', 'Warning');
                    warningShown = true; // Set the flag to true after showing the warning
                }

                // Revert to the last valid selection
                $(this).val(previousSelection);
                $(this).trigger('change');
            } else {
                // Update previous selection if within limit
                previousSelection = selectedOptions; // Store the valid selection
            }
            updateSelectedCount(); // Update count display after selection change
        });

        // Initial count update
        updateSelectedCount();
    });
</script>
@endpush
