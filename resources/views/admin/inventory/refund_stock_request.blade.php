@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('Inventory') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Inventory') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-inventory-index') }}">{{ __('Inventory') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct"> @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show session-message" role="alert">
                                <strong>{{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="alert alert-danger alert-dismissible fade show session-message" role="alert">
                                <strong>{{ session('warning') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @include('includes.admin.form-success')
                        <div class="table-responsiv">
                            <table id="ajaxTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th >{{ __('Request create') }}</th>
                                    <th>{{ __('Recusition no') }}</th>
                                    <th>{{ __('Warehouse From') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection



@section('scripts')

    {{-- DATA TABLE --}}


    <script type="text/javascript">


        var table = $('#ajaxTable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: "{{route('admin-inventory-receive-stock-request-datatable')}}",
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'warehouse_name', name: 'warehouse_name' },
                { data: 'product_name', name: 'product_name' },
                { data: 'quantity', name: 'quantity' },
                { data: 'status', name: 'status' },
                { data: 'action', searchable: false, orderable: false }
            ],
            language : {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            },
            drawCallback : function( settings ) {
                $('.select').niceSelect();
            }
        });

        document.querySelector('.dataTables_filter input[type="search"]').focus();
    </script>

    {{-- DATA TABLE --}}

@endsection