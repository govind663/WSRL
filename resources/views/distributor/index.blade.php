@extends('layouts.master')

@section('title')
WSRL | Manaage  Distributor
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
                        <h4>Manage  Distributor</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Manage  Distributor
                            </li>
                        </ol>
                    </nav>
                </div>

                @can('product-create')
                <div class="col-md-6 col-sm-12 text-right">
                    <div class="dropdown">
                        <a class="btn btn-primary" href="{{ route('distributor.create') }}">
                            <i class="fa fa-plus" aria-hidden="true"> </i>  Distributor
                        </a>

                    </div>
                </div>
                @endcan
            </div>
        </div>

        <!-- Export Datatable start -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All  Distributor List</h4>
            </div>
            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export1 nowrap p-3">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique Code</th>
                            <th>Name</th>
                            <th>Email Id</th>
                            <th>Mobile Number</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Postal Code</th>
                            <th>Country</th>
                            <th>Status</th>
                            {{-- @can('product-edit') --}}
                            <th class="no-export">Edit</th>
                            {{-- @endcan
                            @can('product-delete') --}}
                            <th class="no-export">Delete</th>
                            {{-- @endcan --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($distributors as $key => $value)
                        <tr>
                            <td class="text-wrap text-justify">{{ ++$key }}</td>
                            <td class="text-wrap text-justify">{{ $value->distributor_code }}</td>
                            <td class="text-wrap text-justify">{{ $value->distributor_name }}</td>
                            <td class="text-wrap text-justify">{{ $value->email }}</td>
                            <td class="text-wrap text-justify">{{ $value->contact_person }}</td>
                            <td class="text-wrap text-justify">{!! $value->address !!}</td>
                            <td class="text-wrap text-justify">{{ $value->city }}</td>
                            <td class="text-wrap text-justify">{{ $value->state }}</td>
                            <td class="text-wrap text-justify">{{ $value->postal_code }}</td>
                            <td class="text-wrap text-justify">{{ $value->country }}</td>
                            <td class="text-wrap text-justify">
                                @if ($value->status == 'active')
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">In Active</span>
                                @endif
                            </td>

                            {{-- @can('product-edit') --}}
                            <td class="no-export">
                                <a href="{{ route('distributor.edit', $value->id) }}">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="micon dw dw-pencil-1"></i>
                                    </button>
                                </a>
                            </td>
                            {{-- @endcan --}}
                            {{-- @can('product-delete') --}}
                            <td class="no-export">
                                <form action="{{ route('distributor.destroy', $value->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">
                                        <i class="micon dw dw-trash"></i>
                                    </button>
                                </form>
                            </td>
                            {{-- @endcan --}}
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
               title: 'All  Distributor List',
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
