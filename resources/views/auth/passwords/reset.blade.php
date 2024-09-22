<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Meta Description -->
    <meta name="description" content="Reset Password Admin">

    <!-- Meta Keyword-->
    <meta name="keywords" content="Admin, Reset Password">

    <!-- Responsive Meta-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta Author-->
    <meta name="author" content="Admin">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Admin || Reset Password">
    <meta property="og:description" content="Reset Password Admin">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('/assets/images/logo.png') }}">

    <!-- Facebook Meta -->
    <meta property="og:site_name" content="Admin">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:secure_url" content="{{ asset('/assets/images/logo.png') }}">
    <meta property="og:image:type" content="image/png">

    <!-- Twitter Meta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@admin">
    <meta name="twitter:creator" content="@admin">
    <meta name="twitter:title" content="Admin || Reset Password">
    <meta name="twitter:description" content="Reset Password Admin">
    <meta name="twitter:image" content="{{ asset('/assets/images/logo.png') }}">
    <meta name="twitter:image:alt" content="Admin">

    <!-- Apple Meta -->
    <meta name="apple-mobile-web-app" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Admin">

    <!-- Favicon-->
    <link rel="apple-touch-icon" href="{{ asset('/assets/images/favicon.png') }}">
    <link rel="icon" href="{{ asset('/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('/assets/images/favicon.png') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title-->
    <title>Admin || Reset Password</title>

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/font-awesome.css') }}">

    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/icofont.css') }}">

    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/themify.css') }}">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/flag-icon.css') }}">

    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/feather-icon.css') }}">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/bootstrap.css') }}">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('/assets/css/color-1.css') }}" media="screen">

    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/responsive.css') }}">

    <!-- Toaster Message -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<body>
    <!-- Reset Password page start-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5">
                <img class="bg-img-cover bg-center" src="{{ asset('/assets/images/login/2.jpg') }}" alt="looginpage">
            </div>
            <div class="col-xl-7 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div>
                            <a class="logo text-start" href="{{ route('admin.password.reset') }}">
                                <img class="img-fluid for-dark" src="{{ asset('/assets/images/logo/logo.png') }}" alt="looginpage">
                                <img class="img-fluid for-light" src="{{ asset('/assets/images/logo/logo_dark.png') }}" alt="looginpage">
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('admin.password.update') }}" enctype="multipart/form">
                                @csrf
                                <h4>{{ __('Reset Password') }}</h4>
                                {{-- <p>{{ __('Enter your personal details to create account') }}</p> --}}

                                <div class="form-group">
                                    <label class="col-form-label"><b>{{ __('Email Id') }} : <span class="text-danger">*</span></b></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Enter Email ID">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label"><b>{{ __('Password') }} : <span class="text-danger">*</span></b></label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Enter Passwoed">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label"><b>{{ __('Confirm Password') }} : <span class="text-danger">*</span></b></label>
                                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="password_confirmation" placeholder="Enter Confirm Passwoed">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button class="btn btn-primary btn-block w-100 mt-2" type="submit">{{ __('Reset Password') }}</button>

                                <p class="mt-4 mb-0 text-left">
                                   <b>Already have an account?
                                        <a class="ms-2" href="{{ route('admin.login') }}">{{ __('Sign In') }}</a>
                                   </b>
                                </p>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- login page end-->

    <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ asset('/assets/js/config.js') }}"></script>
    <script src="{{ asset('/assets/js/script.js') }}"></script>

    <script>
        @if(Session::has('message'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.success("{{ session('message') }}");
        @endif

        @if(Session::has('error'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.error("{{ session('error') }}");
        @endif

        @if(Session::has('info'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.info("{{ session('info') }}");
        @endif

        @if(Session::has('warning'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.warning("{{ session('warning') }}");
        @endif
    </script>
</body>

</html>
