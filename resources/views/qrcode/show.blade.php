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

        <div class="page-header">
            <div class="row">
                <div class="container">
                    <h1>Generated QR Codes</h1>
                    <h3>Unique Number: {{ $qrCode->unique_number }}</h3>
                    <h4>Quantity: {{ $qrCode->quantity }}</h4>

                    <div class="qr-codes">
                        <h5>Internal QR Codes:</h5>
                        @foreach ($internalQRCodes as $code)
                            <div class="qr-code">
                                @if (file_exists(public_path('wsrl/images/qrcodes/' . $code)))

                                    <img src="{{ asset('wsrl/images/qrcodes/' . $code) }}" alt="Internal QR Code" />
                                @else
                                    <p>Image not found: {{ $code }}</p>
                                @endif
                                <p>{{ $code }}</p>
                            </div>
                        @endforeach

                        <h5>External QR Codes:</h5>
                        @foreach ($externalQRCodes as $code)
                            <div class="qr-code">
                                @if (file_exists(public_path('wsrl/images/qrcodes/' . $code)))
                                    <img src="{{ asset('wsrl/images/qrcodes/' . $code) }}" alt="External QR Code" />
                                @else
                                    <p>Image not found: {{ $code }}</p>
                                @endif
                                <p>{{ $code }}</p>
                            </div>
                        @endforeach
                    </div>

                    <button onclick="printDiv('qr-codes')" class="btn btn-primary">Print QR Codes</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer Start -->
    <x-footer />
    <!-- Footer Start -->
</div>
@endsection

@push('scripts')
<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
@endpush
