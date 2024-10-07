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
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('/assets/vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('/assets/vendors/images/favicon-16x16.png') }}" />

    <!-- Title -->
    <title>Mobile Verification</title>

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
                            <h2 class="text-center text-primary">Mobile Verification</h2>
                        </div>

                        <form method="POST" action="#" aria-label="{{ __('Mobile Verification') }}" enctype="multipart/form">
                            @csrf
                            <div class="input-group custom">
                                <input type="text" class="form-control form-control-lg" placeholder="Enter Mobile Number" />
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i class="fa fa-mobile-phone" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-block">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

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
