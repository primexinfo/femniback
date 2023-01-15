@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Order Invoice') }} <a class="add-btn" href="javascript:history.back();"><i
                                    class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Invoice') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="order-table-wrap">
            <div class="invoice-wrap">
                <div class="invoice__title">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="invoice__logo text-left">
                                <img src="{{ asset('assets/images/'.$gs->invoice_logo) }}" alt="woo commerce logo">
                            </div>
                        </div>
                        <div class="col-lg-6 text-right">
                            <a class="btn  add-newProduct-btn print" href="{{route('admin-inventory-stock-request-invoice-print',$inventoryRequests->id)}}"
                               target="_blank"><i class="fa fa-print"></i> {{ __('Print Invoice') }}</a>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row invoice__metaInfo mb-4">
                    <div class="col-lg-6">
                        <div class="invoice__orderDetails">

                            <p><strong>{{ __('Recusition Details') }} </strong></p>
                            <span><strong>{{ __('Invoice Number') }} :</strong> {{ sprintf("%'.08d", $inventoryRequests->id) }}</span><br>
                            <span><strong>{{ __('Recusition order Date') }} :</strong> {{ date('d-M-Y',strtotime($inventoryRequests->created_at)) }}</span><br>
                            <span><strong>{{ __('Recusition no') }} :</strong> {{$inventoryRequests->invoice_no}}</span><br>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="invoice__orderDetails">
                            <p><strong>{{ __('Warehouse Details') }} </strong></p>
                            <span><strong>{{ __('Warehouse from') }} :</strong> {{ $inventoryRequests->warehouse_name_from }}</span><br>
                            <span><strong>{{ __('Warehouse to') }} :</strong> {{$inventoryRequests->warehouse_name_to}}</span><br>
                            <span><strong>{{ __('Barcode') }} :</strong> <svg id="barcode"></svg></span><br>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="invoice_table">
                            <div class="mr-table">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                           width="100%" >
                                        <thead>
                                        <tr>
                                            <th>{{ __('Product name') }}</th>
                                            <th>{{ __('Quantity') }}</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{$inventoryRequests->product_name}}</td>
                                            <td>{{$inventoryRequests->quantity}}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->

@endsection
@section('scripts')
    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.3/dist/JsBarcode.all.min.js"></script>
    <script type="text/javascript">

        JsBarcode("#barcode", "{{$inventoryRequests->invoice_no}}", {
            format: "code128",
            width: 1,
            height: 15,
            displayValue: false
        });

        setTimeout(function () {
            window.close();
        }, 500);


    </script>
    @endsection