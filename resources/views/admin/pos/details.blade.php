@extends('layouts.admin')

@section('styles')

    <style type="text/css">
        .order-table-wrap table#example2 {
            margin: 10px 20px;
        }

    </style>

@endsection


@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Pos Details') }} <a class="add-btn" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Pos history') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Pos Details') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="order-table-wrap">
            @include('includes.admin.form-both')
            <div class="row">

                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Pos Details') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th class="45%" width="45%">{{ __('Pos ID') }}</th>
                                    <td width="10%">:</td>
                                    <td class="45%" width="45%">{{$pos->pos_no}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Total Cost') }}</th>
                                    <td width="10%">:</td>
                                    {{--<td width="45%">{{$pos->currency_sign}}{{ round($pos->pay_amount * $pos->currency_value , 2) }}</td>--}}
                                    <td width="45%">{{ $pos->grand_total  }}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Ordered Date') }}</th>
                                    <td width="10%">:</td>
                                    <td width="45%">{{\Carbon\Carbon::parse($pos->created_at)->format('d M, Y h:i A')}}</td>
                                </tr>

                                <tr>
                                    <th width="45%">{{ __('Payment Method') }}</th>
                                    <td width="10%">:</td>
                                    <td width="45%">
                                        @if($pos->payment_type == 1)
                                            Cash
                                        @elseif($pos->payment_type == 2)
                                            Bkash
                                        @else
                                            Part payment
                                        @endif

                                    </td>
                                </tr>

                                {{--@if($pos->method != "Cash On Delivery")--}}
                                    {{--@if($pos->method=="Stripe")--}}
                                        {{--<tr>--}}
                                            {{--<th width="45%">{{$pos->method}} {{ __('Charge ID') }}</th>--}}
                                            {{--<td width="10%">:</td>--}}
                                            {{--<td width="45%">{{$pos->charge_id}}</td>--}}
                                        {{--</tr>--}}
                                    {{--@endif--}}
                                    {{--<tr>--}}
                                        {{--<th width="45%">{{$pos->method}} {{ __('Transaction ID') }}</th>--}}
                                        {{--<td width="10%">:</td>--}}
                                        {{--<td width="45%">{{$pos->txnid}}</td>--}}
                                    {{--</tr>--}}
                                {{--@endif--}}

                                <th width="45%">{{ __('Payment Status') }}</th>
                                <th width="10%">:</th>
                                <td width="45%"><span class='badge badge-success'>Paid</span></td>

                                {{--@if(!empty($pos->order_note))--}}
                                    {{--<th width="45%">{{ __('Order Note') }}</th>--}}
                                    {{--<th width="10%">:</th>--}}
                                    {{--<td width="45%">{{$pos->order_note}}</td>--}}
                                {{--@endif--}}

                                </tbody>
                            </table>
                        </div>
                        {{--<div class="footer-area">--}}
                            {{--<a href="{{ route('admin-order-invoice',$pos->id) }}" class="mybtn1"><i class="fas fa-eye"></i> {{ __('View Invoice') }}</a>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Billing Details') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th width="45%">{{ __('Warehouse name') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$pos->warehouse_name}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Sale admin name') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$pos->admin_name}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Sale admin role') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        @if($pos->admin_role  == 'Administrator')
                                            Administrator
                                        @elseif($pos->admin_role  == 'warehouseAdmin')
                                            Warehouse admin
                                        @else
                                            Pos admin
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Customer Name') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$pos->customer_name}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Customer Email') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$pos->customer_email}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Customer Phone') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$pos->customer_phone}}</td>
                                </tr>
                                {{--@if($pos->coupon_code != null)--}}
                                    {{--<tr>--}}
                                        {{--<th width="45%">{{ __('Coupon Code') }}</th>--}}
                                        {{--<th width="10%">:</th>--}}
                                        {{--<td width="45%">{{$pos->coupon_code}}</td>--}}
                                    {{--</tr>--}}
                                {{--@endif--}}
                                {{--@if($pos->coupon_discount != null)--}}
                                    {{--<tr>--}}
                                        {{--<th width="45%">{{ __('Coupon Discount') }}</th>--}}
                                        {{--<th width="10%">:</th>--}}
                                        {{--@if($gs->currency_format == 0)--}}
                                            {{--<td width="45%">{{ $pos->currency_sign }}{{ $pos->coupon_discount }}</td>--}}
                                        {{--@else--}}
                                            {{--<td width="45%">{{ $pos->coupon_discount }}{{ $pos->currency_sign }}</td>--}}
                                        {{--@endif--}}
                                    {{--</tr>--}}
                                {{--@endif--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12 order-details-table">
                    <div class="mr-table">
                        <h4 class="title">{{ __('Products Ordered') }}</h4>
                        <div class="table-responsiv">
                            <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>

                                <tr>
                                    <th width="">{{ __('Product ID#') }}</th>
                                    <th>{{ __('Product Title') }}</th>
                                    <th width="">{{ __('Product Quantity') }}</th>
                                    <th width="">{{ __('Product Price') }}</th>
                                    <th width="">{{ __('Total Price') }}</th>
                                </tr>

                                </thead>
                                <tbody>
                                @php
                                    $sale_items =  unserialize(bzdecompress(utf8_decode($pos->sale_item)));

                                @endphp

                                @foreach($sale_items as $sale_item)
                                    <tr>
                                        @foreach($sale_item as $key=>$value)
                                            <td>{{$value}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                       <td colspan="3"></td>
                                       <td>Grand Total</td>
                                       <td>{{$pos->grand_total}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                {{--<div class="col-lg-12 text-center mt-2">--}}
                    {{--<a class="btn sendEmail send" href="javascript:;" class="send" data-email="{{ $pos->customer_email }}" data-toggle="modal" data-target="#vendorform">--}}
                        {{--<i class="fa fa-send"></i> {{ __('Send Email') }}--}}
                    {{--</a>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->
    
    {{-- MESSAGE MODAL --}}
    {{--<div class="sub-categori">--}}
        {{--<div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">--}}
            {{--<div class="modal-dialog" role="document">--}}
                {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                        {{--<h5 class="modal-title" id="vendorformLabel">{{ __('Send Email') }}</h5>--}}
                        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                            {{--<span aria-hidden="true">&times;</span>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body">--}}
                        {{--<div class="container-fluid p-0">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-md-12">--}}
                                    {{--<div class="contact-form">--}}
                                        {{--<form id="emailreply">--}}
                                            {{--{{csrf_field()}}--}}
                                            {{--<ul>--}}
                                                {{--<li>--}}
                                                    {{--<input type="email" class="input-field eml-val" id="eml" name="to" placeholder="{{ __('Email') }} *" value="" required="">--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<input type="text" class="input-field" id="subj" name="subject" placeholder="{{ __('Subject') }} *" required="">--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<textarea class="input-field textarea" name="message" id="msg" placeholder="{{ __('Your Message') }} *" required=""></textarea>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                            {{--<button class="submit-btn" id="emlsub" type="submit">{{ __('Send Email') }}</button>--}}
                                        {{--</form>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{-- MESSAGE MODAL ENDS --}}

    {{-- ORDER MODAL --}}

    {{--<div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="submit-loader">--}}
                    {{--<img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">--}}
                {{--</div>--}}
                {{--<div class="modal-header d-block text-center">--}}
                    {{--<h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                {{--</div>--}}

                {{--<!-- Modal body -->--}}
                {{--<div class="modal-body">--}}
                    {{--<p class="text-center">{{ __("You are about to update the order's status.") }}</p>--}}
                    {{--<p class="text-center">{{ __('Do you want to proceed?') }}</p>--}}
                {{--</div>--}}

                {{--<!-- Modal footer -->--}}
                {{--<div class="modal-footer justify-content-center">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>--}}
                    {{--<a class="btn btn-success btn-ok order-btn">{{ __('Proceed') }}</a>--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{-- ORDER MODAL ENDS --}}


@endsection


@section('scripts')

    {{--<script type="text/javascript">--}}
        {{--$('#example2').dataTable( {--}}
            {{--"ordering": false,--}}
            {{--'lengthChange': false,--}}
            {{--'searching'   : false,--}}
            {{--'ordering'    : false,--}}
            {{--'info'        : false,--}}
            {{--'autoWidth'   : false,--}}
            {{--'responsive'  : true--}}
        {{--} );--}}
    {{--</script>--}}

    {{--<script type="text/javascript">--}}
        {{--$(document).on('click','#license' , function(e){--}}
            {{--var id = $(this).parent().find('input[type=hidden]').val();--}}
            {{--var key = $(this).parent().parent().find('input[type=hidden]').val();--}}
            {{--$('#key').html(id);--}}
            {{--$('#license-key').val(key);--}}
        {{--});--}}
        {{--$(document).on('click','#license-edit' , function(e){--}}
            {{--$(this).hide();--}}
            {{--$('#edit-license').show();--}}
            {{--$('#license-cancel').show();--}}
        {{--});--}}
        {{--$(document).on('click','#license-cancel' , function(e){--}}
            {{--$(this).hide();--}}
            {{--$('#edit-license').hide();--}}
            {{--$('#license-edit').show();--}}
        {{--});--}}

        {{--$(document).on('submit','#edit-license' , function(e){--}}
            {{--e.preventDefault();--}}
            {{--$('button#license-btn').prop('disabled',true);--}}
            {{--$.ajax({--}}
                {{--method:"POST",--}}
                {{--url:$(this).prop('action'),--}}
                {{--data:new FormData(this),--}}
                {{--dataType:'JSON',--}}
                {{--contentType: false,--}}
                {{--cache: false,--}}
                {{--processData: false,--}}
                {{--success:function(data)--}}
                {{--{--}}
                    {{--if ((data.errors)) {--}}
                        {{--for(var error in data.errors)--}}
                        {{--{--}}
                            {{--$.notify('<li>'+ data.errors[error] +'</li>','error');--}}
                        {{--}--}}
                    {{--}--}}
                    {{--else--}}
                    {{--{--}}
                        {{--$.notify(data,'success');--}}
                        {{--$('button#license-btn').prop('disabled',false);--}}
                        {{--$('#confirm-delete').modal('toggle');--}}

                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}

@endsection