@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Today\'s Ordered Products') }} <a class="add-btn" href="javascript:history.back();"><i
                            class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Ordered Products') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="order-table-wrap">
        <div class="invoice-wrap">
            <div class="invoice__title">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="invoice__logo text-left">
                           <h4>{{ __('Today\'s Ordered Products') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice_table">
                        <div class="mr-table">
                            <div class="table-responsive">
                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%" >
                                    <thead>
                                        <tr>
                                            <th>{{ __('Product Name') }}</th>
                                            <th>{{ __('Total Quantity') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($result as $key=>$value)
                                        <tr>
                                            <td width="50%">
                                                {{$key}}
                                            </td>
                                            <td>
                                            @php
                                               $total = 0;
                                            @endphp
                                            @foreach($value as $key=>$val)
                                               @php
                                                    $total  = $total + $val['qty'];    
                                               @endphp
                                            @endforeach
                                               {{$total}}
                                            </td>
                                        </tr>
                                        @endforeach
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
</div>
</div>
</div>

@endsection