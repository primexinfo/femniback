@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
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
        <div class="content-area">

            <div class="add-product-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description">
                            <div class="body-area">
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

                                @if (session('warning'))
                                    <div class="alert alert-danger alert-dismissible fade show session-message" role="alert">
                                        <strong>{{ session('warning') }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                @include('includes.admin.form-success')

                                <form id="" action="{{route('inventory-store')}}" method="POST" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="left-area">
                                                <h4 class="heading ">{{ __('Product Name') }}* </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <input type="text" class="input-field" name="product_name" id='employee_search' placeholder="--search--">
                                            <input type="hidden" name="product_id" id='employee_search_id'>
                                            <input type="hidden" name="product_type" id="product_type">
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <div class="left-area">
                                                <h4 class="heading ">{{ __('Amount/quantity') }}* </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <input type="text" class="input-field" name="amount_per_quantity" required>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <div class="left-area">
                                                <h4 class="heading ">{{ __('Total Quantity') }}* </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <input type="text" class="input-field" name="quantity" required>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <div class="left-area">
                                                <h4 class="heading ">{{ __('Total Amount') }}* </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <input type="text" class="input-field" name="amount" required>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">

                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <button class="addProductSubmit-btn" type="submit">{{ __('Submit') }}</button>
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

        @endsection



@section('scripts')
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
                            console.log(ui.item);
                            $('#employee_search').val(ui.item.label);
                            $('#employee_search_id').val(ui.item.value);
                            $('#product_type').val(ui.item.type);
                            return false;
                        }
                    });

                });
            </script>


@endsection