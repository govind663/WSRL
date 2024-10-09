@extends('layouts.master')

@section('title')
WSRL | WSRL
@endsection

@push('styles')
@endpush

@section('content')
<div class="xs-pd-20-10 pd-ltr-20">

    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Dashboard</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Dashboard
                        </li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>

    <div class="title pb-20">
        <h2 class="h3 mb-0">Project Overview</h2>
    </div>

    <div class="row pb-10">
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-900 font-24 text-dark">
                            {{ $totalNumberProductValidateCount ?? 0 }}
                        </div>
                        <div class="font-14 text-primary weight-500">
                            Total Number of Product Validated by Distributor
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#00eccf">
                            <i class="icon-copy dw dw-user1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-900 font-24 text-dark">
                            {{ $totalNumberDoctorValidateCount ?? 0 }}
                        </div>
                        <div class="font-14 text-primary weight-500">
                            Total Number of Product Validated by Doctor
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#ff5b5b">
                            <span class="fa fa-stethoscope"></span>
                        </div>
                    </div>
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
@endpush
