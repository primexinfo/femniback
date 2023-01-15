@extends('layouts.front')
@section('content')

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pages">
                        <li>
                            <a href="{{ route('front.index') }}">
                                {{ $langg->lang17 }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('payment.return') }}">
                                {{ $langg->lang169 }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <section class="checkout">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-area mb-0 pb-0">
                        <div class="checkout-process">
                            <div class="checkout-area">
                                <div class="content-box">
                                    <div class="personal-info">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                {!!
                                                    aamarpay_post_button([
                                                    'cus_name'  => $customer_name, // Customer name
                                                    'cus_email' => $customer_email, // Customer email
                                                    'cus_phone' => $customer_phone // Customer Phone
                                                    ],$paymentAmount,'<i class="fa fa-money">Payment</i>', 'btn btn btn-success')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




@endsection

