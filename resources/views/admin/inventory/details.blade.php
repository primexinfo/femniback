@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('Inventory') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Warehouses') }}</h4>
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
                        <div class="table-responsiv">
                            <table id="" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <td width="30%%"><b>{{ __('Warehouse Name') }}</b></td>
                                    <td>{{$inventory[0]->warehouse_name}}</td>

                                </tr>
                                <tr>
                                    <td><b>{{ __('Product name') }}</b></td>
                                    <td>{{$inventory[0]->product_name}}</td>
                                </tr>

                                </thead>
                            </table>
                            <table id="ajaxTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __('Stock Entry date') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventory as $key=>$value)
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($value->created_at)->format('d M, Y')}}</td>
                                            <td>{{$value->quantity}}</td>
                                            <td>{{$value->amount}}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                            <div class="action-list">
                               {{ $inventory->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection



@section('scripts')



@endsection