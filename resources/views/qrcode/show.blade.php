<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/vendors/images/favicon-16x16.png') }}" />

    <!-- Title -->
    <title>QR Code Details</title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendors/styles/style.css') }}" />

</head>

<body>
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="login.html">
                    <img src="{{ asset('/assets/vendors/images/deskapp-logo.svg') }}" alt="" />
                </a>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('/assets/vendors/images/forgot-password.png') }}" alt="" />
                </div>
                <div class="col-md-6">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            @if ($isInternal)
                                <h2 class="text-center text-primary">Product Details</h2>
                            @elseif ($isExternal)
                                <h2 class="text-center text-primary">Distributor Details</h2>
                            @endif
                        </div>
                        <!-- QR Code Details Section -->
                        <div class="pb-20">
                            <p><strong>Unique Number:</strong> {{ $qrCode->unique_number }}</p>

                            <!-- Displaying QR code type and scanned number -->
                            @if ($isInternal)
                                <p><strong>Type:</strong> Internal</p>
                                <p><strong>Scanned Serial Number:</strong> {{ $scannedNumber }}</p>
                                <p><strong>Internal QR Code Count:</strong> {{ $internalQrCodeCount }}</p> <!-- Display the count -->

                                <!-- Displaying related user details -->
                                <p><strong>User Name:</strong> {{ $qrCode->user->name ?? 'N/A' }}</p>
                                <p><strong>User Email:</strong> {{ $qrCode->user->email ?? 'N/A' }}</p>
                            @elseif ($isExternal)
                                <p><strong>Type:</strong> External</p>
                                <p><strong>Scanned Serial Number:</strong> {{ $scannedNumber }}</p>
                                <p><strong>External QR Code Count:</strong> {{ $externalQrCodeCount }}</p> <!-- Display the count -->

                                <!-- Displaying related product details -->
                                <p><strong>Product Name:</strong> {{ $qrCode->product->name ?? 'N/A' }}</p>
                                <p><strong>Product Description:</strong> {!! $qrCode->product->description ?? 'N/A' !!}</p>
                            @else
                                <p><strong>Type:</strong> Not Found</p>
                            @endif

                            <p><strong>Inserted Date:</strong> {{ $qrCode->inserted_dt }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="{{ asset('assets/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('asset/vendors/scripts/layout-settings.js') }}"></script>
</body>

</html>
