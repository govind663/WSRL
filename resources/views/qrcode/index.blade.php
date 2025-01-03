@extends('layouts.master')

@section('title')
WSRL | Manaage  QrCode
@endsection

@push('styles')
<style>
    .flex-wrap {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Manage  QrCode</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Manage  QrCode
                            </li>
                        </ol>
                    </nav>
                </div>

                @can('qrcode-create')
                <div class="col-md-6 col-sm-12 text-right">
                    <div class="dropdown">
                        <a class="btn btn-primary" href="{{ route('qrcode.create') }}">
                            <i class="fa fa-plus" aria-hidden="true"> </i>  Generate QrCode
                        </a>

                    </div>
                </div>
                @endcan
            </div>
        </div>

        <!-- Export Datatable start -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All QR Code List</h4>
            </div>
            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export1 nowrap p-3">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique Number</th>
                            <th>Product</th>
                            <th>Total <br> Quantity</th>
                            <th>Avilable <br> Quantity</th>
                            {{-- <th>Internal QrCode </th>
                            <th>External QrCode </th> --}}
                            <th>Internal Scan <br> QrCode Count</th>
                            <th>External Scan <br> QrCode Count</th>

                            @can('qrcode-edit')
                            <th class="no-export">Print</th>
                            @endcan
                            @can('qrcode-delete')
                            <th class="no-export">Delete</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($qrcodes as $key => $value)
                        <tr>
                            <td class="text-wrap text-justify">{{ ++$key }}</td>

                            <td class="text-wrap text-justify">
                                {{ $value->unique_number }}
                            </td>

                            <td class="text-wrap text-justify">
                                {{ $value->product?->name }}
                            </td>

                            <td class="text-wrap text-justify">
                                {{ $value->quantity }}
                            </td>

                            <td class="text-wrap text-justify">
                                {{ $value->avilable_quantity }}
                            </td>

                            {{-- <td class="text-wrap text-justify">
                                @php
                                    $internalQrData = json_decode($value->internal_qr_code, true) ?? [];
                                @endphp
                                @foreach ($internalQrData as $qr)
                                    <span class="badge badge-primary">{{ $qr }}</span>
                                @endforeach
                            </td> --}}

                            {{-- <td class="text-wrap text-justify">
                                @php
                                    $externalQrData = json_decode($value->external_qr_code, true) ?? [];
                                @endphp
                                @foreach ($externalQrData as $qr)
                                    <span class="badge badge-primary">{{ $qr }}</span>
                                @endforeach
                            </td> --}}

                            <td class="text-wrap text-justify">
                                {{ $value->internal_qr_code_scan_count }}
                            </td>

                            <td class="text-wrap text-justify">
                                {{ $value->external_qr_code_scan_count }}
                            </td>

                            @can('qrcode-edit')
                            <td class="no-export">
                                <a href="{{ route('qrcode.showPdf', $value->unique_number) }}" target="_blank">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-print"></i>
                                    </button>
                                </a>
                            </td>
                            @endcan

                            @can('qrcode-delete')
                            <td class="no-export">
                                <form action="{{ route('qrcode.destroy', $value->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">
                                        <i class="micon dw dw-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Export Datatable End -->
    </div>

    <!-- Footer Start -->
    <x-footer />
    <!-- Footer Start -->
</div>
@endsection

@push('scripts')
<script>
    $('.data-table-export1').DataTable({
        scrollCollapse: true,
        autoWidth: true,
        responsive: true,
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search",
            // paginate: {
            //     next: '<i class="ion-chevron-right"></i>',
            //     previous: '<i class="ion-chevron-left"></i>'
            // }
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
            {
                extend: 'csv',
                text: 'Excel',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':not(.no-export)',
                },
               header: true,
               title: 'All  QrCode List',
               orientation: 'landscape',
               pageSize: 'A4',
               customize: function(doc) {
                  doc.defaultStyle.fontSize = 16; //<-- set fontsize to 16 instead of 10
                  doc.defaultStyle.fontFamily = "sans-serif";
                // doc.defaultStyle.font = 'Arial';

               }
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-default',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
        ]
    });
</script>
@endpush
