@extends('layouts.master')

@section('title')
Bhairaav | QR Code Details
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
                        <h4>QR Code Details</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('qrcode.index') }}">Manage Qr Code</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                QR Code Details
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">QR Code Details</h4>
            </div>
            <div class="pb-20">
                <p><strong>Unique Number:</strong> {{ $qrCode->unique_number }}</p>
                <p><strong>User ID:</strong> {{ $qrCode->user_id }}</p>
                <p><strong>Quantity:</strong> {{ $qrCode->quantity }}</p>
                <p><strong>Product ID:</strong> {{ $qrCode->product_id }}</p>
                <p><strong>Internal QR Code Count:</strong> {{ $qrCode->internal_qr_code_count }}</p>
                <p><strong>External QR Code Count:</strong> {{ $qrCode->external_qr_code_count }}</p>
                <p><strong>Inserted Date:</strong> {{ $qrCode->inserted_dt }}</p>
                <p><strong>Inserted By:</strong> {{ $qrCode->inserted_by }}</p>
            </div>
        </div>

    </div>

    <!-- Footer Start -->
    <x-footer />
    <!-- Footer Start -->
</div>
@endsection

@push('scripts')
@endpush
