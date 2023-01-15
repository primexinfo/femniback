@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Pos report') }} <a class="add-btn" href="javascript:history.back();"><i
                                    class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Pos Products') }}</a>
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
                                <h4>{{ __('Pos report') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <form action="{{route('pos-sale-report-filter')}}" method="post">
                    {{csrf_field()}}
                    <div class="row" >
                        <div class="col-lg-3">
                            <label for="">Start date</label>
                            <input type="date" name="start_date" id="start_date" class="input-field" placeholder="Start date">
                        </div>
                        <div class="col-lg-3">
                            <label for="">End date</label>
                            <input type="date" name="end_date" id="end_date" class="input-field" placeholder="End date">
                        </div>
                        <div class="col-lg-4">

                            <label for="">Search product</label>
                            <div class="card" style="height: 150px;position: relative">
                                <div class="card-body" >
                                    <input type="text" class="input-field" id='employee_search' placeholder="--search--">
                                    <input type="hidden" name="product_id" id='employee_search_id'>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-2">
                            <button class="add-btn" id="pos_search">Search</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="invoice_table">
                            <div class="mr-table">
                                <div class="table-responsive">
                                    @if($result)
                                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                               width="100%" >
                                            <thead>
                                            <tr>
                                                <th>{{ __('Product Name') }}</th>
                                                <th>{{ __('Total Quantity') }}</th>
                                                <th>{{ __('Total price') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $grand_total = 0;
                                            @endphp
                                            @foreach($result as $key=>$value)
                                                <tr>
                                                    <td width="50%">
                                                        {{$key}}
                                                    </td>


                                                    @php
                                                        $total = 0;
                                                        $line_total = 0;
                                                    @endphp
                                                    @foreach($value as $key=>$val)
                                                        @php
                                                            $total  = $total + $val['quantity'];
                                                            $line_total  = $line_total + $val['line_total'];
                                                        @endphp

                                                    @endforeach

                                                    <td>
                                                        {{$total}}
                                                    </td>
                                                    <td>
                                                        {{$line_total}}
                                                        @php

                                                            $grand_total  = $grand_total + $line_total;
                                                        @endphp
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="2">Total</td>
                                                <td style="text-align: left">{{$grand_total}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    @endif
                                    @if($pro)
                                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                               width="100%" >
                                            <thead>
                                            <tr>
                                                <th>{{ __('Sale date') }}</th>
                                                <th>{{ __('Product Name') }}</th>
                                                <th>{{ __('Total Quantity') }}</th>
                                                <th>{{ __('Total price') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $grand_total = 0;
                                            @endphp

                                            @foreach($pro as $key=>$value)
                                                <tr>
                                                    <td>{{\Carbon\Carbon::parse($value['created_at'])->format('d M, Y h:i A')}}</td>
                                                    <td>{{$value['product_name']}}</td>
                                                    <td>{{$value['quantity']}}</td>
                                                    <td>{{$value['line_total']}}</td>
                                                    @php
                                                        $grand_total = $grand_total + $value['line_total'];
                                                    @endphp
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3">Total: </td>
                                                <td style="text-align: left">{{$grand_total}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    @endif
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
    <!-- Script -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

            $( "#employee_search" ).autocomplete({
                source: function( request, response ) {
                    // Fetch data
                    $.ajax({
                        url:"{{route('Autocomplte.getAutocomplte')}}",
                        type: 'post',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                select: function (event, ui) {
                    $('#employee_search').val(ui.item.label);

                    $('#employee_search_id').val(ui.item.value);
                    return false;
                }
            });

        });
    </script>
@endsection