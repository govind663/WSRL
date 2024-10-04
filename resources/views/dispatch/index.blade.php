@extends('layouts.master')

@section('title')
WSRL | Manaage  Dispatch
@endsection

@push('styles')
{{-- <style>
    .flex-wrap {
        display: none !important;
    }
</style> --}}
@endpush

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Manage  Dispatch</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Manage  Dispatch
                            </li>
                        </ol>
                    </nav>
                </div>

                @can('dispatch-create')
                <div class="col-md-6 col-sm-12 text-right">
                    <div class="dropdown">
                        <a class="btn btn-primary" href="{{ route('dispatch.create') }}">
                            <i class="fa fa-plus" aria-hidden="true"> </i> New Dispatch
                        </a>

                    </div>
                </div>
                @endcan
            </div>
        </div>

        <!-- Export Datatable start -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All  Dispatch List</h4>
            </div>
            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export1 nowrap p-3">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Dispatch Code</th>
                            <th>Distributor Name</th>
                            <th>Product Name</th>
                            <th>Dispatch Date</th>
                            <th>Status</th>
                            @can('dispatch-edit')
                            <th class="no-export">Edit</th>
                            @endcan
                            @can('dispatch-delete')
                            <th class="no-export">Delete</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dispatches as $key => $value)
                        <tr>
                            <td class="text-wrap text-justify">{{ ++$key }}</td>
                            <td class="text-wrap text-justify">{{ $value->dispatch_code }}</td>
                            <td class="text-wrap text-justify">
                                {{ $value->distributor->distributor_name }}
                            </td>
                            <td class="text-wrap text-justify">
                                {{ $value->product->name }}
                            </td>
                            <td class="text-wrap text-justify">
                                {{ date('d-m-Y', strtotime($value->dispatched_at)) }}
                            </td>
                            <td class="text-wrap text-justify">
                                @if ($value->dispatch_status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                                @elseif ($value->dispatch_status == 'dispatched')
                                <span class="badge badge-primary">Dispatched</span>
                                @elseif ($value->dispatch_status == 'canceled')
                                <span class="badge badge-danger">Canceled</span>
                                @elseif ($value->dispatch_status == 'completed')
                                <span class="badge badge-success">Completed</span>
                                @endif
                            </td>

                            @can('dispatch-edit')
                            <td class="no-export">
                                <a href="{{ route('dispatch.edit', $value->id) }}">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="micon dw dw-pencil-1"></i>
                                    </button>
                                </a>
                            </td>
                            @endcan
                            @can('dispatch-delete')
                            <td class="no-export">
                                <form action="{{ route('dispatch.destroy', $value->id) }}" method="post">
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
               title: 'All  Dispatch List',
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
