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
                        <a class="btn  add-newProduct-btn print" href="{{route('admin-order-print',$order->id)}}"
                        target="_blank"><i class="fa fa-print"></i> {{ __('Print Invoice') }}</a>
                    </div>
                </div>
            </div>
            <br>
            @if ($errors->any())
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
            <div class="row invoice__metaInfo mb-4">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">
                        
                        <p><strong>{{ __('Order Details') }} </strong></p>
                        <span><strong>{{ __('Invoice Number') }} :</strong> {{ sprintf("%'.08d", $order->id) }}</span><br>
                        <span><strong>{{ __('Order Date') }} :</strong> {{ date('d-M-Y',strtotime($order->created_at)) }}</span><br>
                        <span><strong>{{  __('Order ID')}} :</strong> {{ $order->order_number }}</span><br>
                        @if($order->dp == 0)
                        <span> <strong>{{ __('Shipping Method') }} :</strong>
                            @if($order->shipping == "pickup")
                            {{ __('Pick Up') }}
                            @else
                            {{ __('Ship To Address') }}
                            @endif
                        </span><br>
                        @endif
                        <span> <strong>{{ __('Payment Method') }} :</strong> {{$order->method}}</span>
                    </div>
                </div>
            </div>
            <div class="row invoice__metaInfo">
           @if($order->dp == 0)
                <div class="col-lg-6">
                        <div class="invoice__shipping">
                            <p><strong>{{ __('Shipping Address') }}</strong></p>
                           <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name}}</span><br>
                           <span><strong>{{ __('Customer Phone') }}</strong>: {{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}</span><br>
                           <span><strong>{{ __('Address') }}</strong>: {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}</span><br>
                           <span><strong>{{ __('City') }}</strong>: {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}</span><br>
                           {{--<span><strong>{{ __('Country') }}</strong>: {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}</span>--}}

                        </div>
                </div>

            @endif

                <div class="col-lg-6">
                        <div class="buyer">
                            <p><strong>{{ __('Billing Details') }}</strong></p>
                            <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->customer_name}}</span><br>
                            <span><strong>{{ __('Customer Phone') }}</strong>: {{ $order->customer_phone}}</span><br>
                            <span><strong>{{ __('Address') }}</strong>: {{ $order->customer_address }}</span><br>
                            <span><strong>{{ __('City') }}</strong>: {{ $order->customer_city }}</span><br>
                            {{--<span><strong>{{ __('Country') }}</strong>: {{ $order->customer_country }}</span>--}}
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
                                            <th>{{ __('Product') }}</th>
                                            <th>{{ __('Details') }}</th>
                                            <th>{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
                                            $subtotal = 0;
                                            $tax = 0;
                                        @endphp
                                        @foreach($cart as $product)
                                        <tr>
                                            <td width="50%">

                                                {{$product['product_name']}}
                                            </td>
                                            <td>
                                                @if($product['product_size'])
                                               <p>
                                                    <strong>Size :</strong> {{$product['product_size']}}
                                               </p>
                                               @endif
                                               @if($product['product_color'])
                                                <p>
                                                        <strong>color :</strong> <span
                                                        style="width: 40px; height: 20px; display: block; background: {{$product['product_color']}};"></span>
                                                </p>
                                                @endif
                                                <p>
                                                        <strong>Price :</strong> {{$order->currency_sign}}{{ round($product['total_price'] * $order->currency_value , 2) }}
                                                </p>
                                                <p>
                                                    <strong>Qty :</strong> {{$product['product_quantity']}}
                                                </p>

                                            </td>


                                            <td>{{$order->currency_sign}}{{ round($product['total_price'] * $order->currency_value , 2) }}
                                            </td>
                                            @php
                                            $subtotal += round($product['total_price'] * $order->currency_value, 2);
                                            @endphp

                                        </tr>

                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="2">{{ __('Subtotal') }}</td>
                                            <td>{{$order->currency_sign}}{{ round($subtotal, 2) }}</td>
                                        </tr>
                                        @if($order->shipping_cost != 0)
                                        <tr>
                                            <td colspan="2">{{ __('Shipping Cost') }}({{$order->currency_sign}})</td>
                                            <td>{{ round($order->shipping_cost , 2) }}</td>
                                        </tr>
                                        @endif

                                        @if($order->packing_cost != 0)
                                        <tr>
                                            <td colspan="2">{{ __('Packaging Cost') }}({{$order->currency_sign}})</td>
                                            <td>{{ round($order->packing_cost , 2) }}</td>
                                        </tr>
                                        @endif

                                        @if($order->tax != 0)
                                        <tr>
                                            <td colspan="2">{{ __('TAX') }}({{$order->currency_sign}})</td>
                                            @php
                                            $tax = ($subtotal / 100) * $order->tax;
                                            @endphp
                                            <td>{{round($tax, 2)}}</td>
                                        </tr>
                                        @endif
                                        @if($order->coupon_discount != null)
                                        <tr>
                                            <td colspan="2">{{ __('Coupon Discount') }}({{$order->currency_sign}})</td>
                                            <td>{{round($order->coupon_discount, 2)}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="1"></td>
                                            <td>{{ __('Total') }} @if($order->adjust_remark != '') (Adjusted) @endif</td>
                                            <td>{{$order->currency_sign}}{{ round($order->pay_amount * $order->currency_value , 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                @if($order->adjust_remark != '')
                                <div>
                                    <p style="text-align: left">
                                        <b>**Adjust remark: </b>
                                        <span>{{$order->adjust_remark}}</span>
                                    </p>
                                </div>
                                @endif
                                <form action="{{route('admin-order-adjust')}}" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" name="order_id" value="{{$order->id}}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Adjust Remark</label>
                                            <textarea name="adjust_remark" id="adjust_remark" cols="40" rows="3" class="form-control" ></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Total price after adjust</label>
                                            <input type="text" name="pay_amount" id="pay_amount" class="form-control" >
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="add-btn">Adjust</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Content Area End -->
</div>
</div>
</div>

@endsection
@section('script')
    <script>


    </script>
    @endsection