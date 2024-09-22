<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Head Start -->
    <x-head />

    @stack('styles')
</head>

<body>
    {{-- Pre Loader Start --}}
    {{-- <x-pre-loader /> --}}
    {{-- Pre Loader End --}}

    <!-- Header Start -->
    <x-header />
    <!-- Header End -->

    <!-- Sidebar Start -->
    <x-sidebar />
    <!-- Sidebar End -->

    <div class="main-container">
        <!-- Page Wrapper Start -->
        @yield('content')
        <!-- Page Wrapper End -->
    </div>

    <!-- Start Main JS  -->
    <x-main-js />
    <!-- End Main JS  -->

    {{-- Custom Js --}}
    @stack('scripts')
</body>

</html>
