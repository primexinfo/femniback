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
                    <div class="mr-table allproduct">
                        @include('includes.admin.form-success')
                        <div class="row">
                            <div class="col-lg-8">
                                <select name="" id="warehouse_id" required>
                                    <option value="">Select</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button class="add-btn" id="warehouse_click"><i class="fas fa-search"></i>Search</button>
                            </div>

                        </div>
                        <div class="table-responsiv">
                            <table id="ajaxTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __('Warehouse Name') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Last Update') }}</th>
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

        var warehouse_id = "{{Auth::guard('admin')->user()->id}}";
        let url = 'inventory-datatables/'+ warehouse_id;
        inventoryTable();

        $('#warehouse_click').on('click',function () {
            warehouse_id = $('#warehouse_id').find(":selected").val();
            url = 'inventory-datatables/'+ warehouse_id;
            var table = $('#ajaxTable').DataTable({
                ordering: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [
                    { data: 'warehouse_name', name: 'warehouse_name' },
                    { data: 'product_name', name: 'product_name' },
                    { data: 'stock', name: 'stock' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', searchable: false, orderable: false }
                ],
                language : {
                    processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                },
                drawCallback : function( settings ) {
                    $('.select').niceSelect();
                }
            });
        });



       function inventoryTable() {
           var table = $('#ajaxTable').DataTable({
               ordering: false,
               destroy: true,
               retrieve: true,
               paging: false,
               processing: true,
               serverSide: true,
               ajax: url,
               columns: [
                   { data: 'warehouse_name', name: 'warehouse_name' },
                   { data: 'product_name', name: 'product_name' },
                   { data: 'stock', name: 'stock' },
                   { data: 'updated_at', name: 'updated_at' },
                   { data: 'action', searchable: false, orderable: false }
               ],
               language : {
                   processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
               },
               drawCallback : function( settings ) {
                   $('.select').niceSelect();
               }
           });
       }

        // var alwaysFocusedInput = document.querySelector( '.dataTables_filter input[type="search"]' );
        //
        // alwaysFocusedInput.addEventListener( "blur", function() {
        //     setTimeout(() =>{
        //         alwaysFocusedInput.focus();
        //     }, 0);
        // });
        document.querySelector('.dataTables_filter input[type="search"]').focus();

    </script>

    {{-- DATA TABLE --}}

@endsection