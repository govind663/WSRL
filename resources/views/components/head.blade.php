<!-- Basic Page Info -->
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

<!-- Site favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ url('/') }}/assets/vendors/images/apple-touch-icon.png" />
<link rel="icon" type="image/png" sizes="32x32" href="{{ url('/') }}/assets/vendors/images/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="16x16" href="{{ url('/') }}/assets/vendors/images/favicon-16x16.png" />

<!-- Title -->
<title>@yield('title')</title>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="csrf-param" content="_token">

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />

<!-- Datatable CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />

<!-- bootstrap-tagsinput css -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />

<!-- Toaster Message -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
