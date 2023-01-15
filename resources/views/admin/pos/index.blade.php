@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('Pos sale history') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Pos sale histories') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li><a href="javascript:;">{{ __('Pos') }}</a></li>
                        {{--<li>--}}
                            {{--<a href="{{ route('admin-cat-index') }}">{{ __('Main Categories') }}</a>--}}
                        {{--</li>--}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">

                        @include('includes.admin.form-success')

                        <div class="table-responsiv">
                            <table id="ajaxTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Pos number') }}</th>
                                    <th>{{ __('Total amount') }}</th>
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
            ajax: '{{ route('pos-sale-datatable') }}',
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'pos_no', name: 'pos_no' },
                { data: 'grand_total', name: 'grand_total' },
                { data: 'action', searchable: false, orderable: false }

            ],
            language : {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            },
            drawCallback : function( settings ) {
                $('.select').niceSelect();
            }
        });

    </script>

    {{-- DATATABLE ENDS --}}

@endsection