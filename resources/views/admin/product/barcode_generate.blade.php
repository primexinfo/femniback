@extends('layouts.admin')

@section('styles')

    <style type="text/css">
        .order-table-wrap table#example2 {
            margin: 10px 20px;
        }
        #p_cla{
            display: grid;  grid-template-columns: repeat(6,1fr); margin: 2px;
        }
        #f_cla{
            border: 1px dashed black; padding: .5rem 0 .5rem 0; margin-right: .5rem
        }
        #g_cla{
            font-size: 11px; display: block;
        }

        @media print {

            *{
                font-family: calibri, 'sans-serif';
            }
            body{
                overflow: hidden;
                text-align: center;

            }
            #printarea{
                position: relative;
                /*page-break-after:always !important;*/

            }

            #p_cla{
                /*display: grid;  grid-template-columns: repeat(6,1fr); margin: 2px;*/
            }
            #g_cla{
                font-size: 11px; display: block;
                /*padding-left: 45px;*/
            }

            #f_cla{
                border: 1px dashed black; padding: .54rem 0 .5rem 0; margin-right: .5rem;
            }

        }

    </style>

@endsection


@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Barcode generate') }} <a class="add-btn" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Barcode generate') }}</a>
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
                                {{ __('Select product') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <select name="" id="product_select" class="input-field">
                                <option value="">Select</option>
                                @foreach($products as $product)
                                    <option exp_date="{{$product->exp_date}}" mf_date="{{$product->mf_date}}" price="{{$product->price}}" value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('List of product') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <form action="{{route('admin-prod-barcode-generate-create')}}" method="post">
                                {{csrf_field()}}
                                <div id="result_show">

                                </div>
                                <button class="add-btn">Generate</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row">
                <div class="col-lg-12 order-details-table">
                    <div class="mr-table">
                        <h4 class="title mr-5">{{ __('Barcode') }}<button  class="add-btn ml-4" onclick="printDiv();">print</button></h4>

                        <div id="printarea" >
                            @php
                                $x = 0;
                            @endphp
                            @if($data != '')
                                @foreach($data as $da)
                                    <div id="p_cla" >
                                        @for($i=0; $i<$da['quantity']; $i++)
                                            <span id="f_cla" >
                                            <span id="g_cla" >{{$gs->title}}</span>
                                            <svg id="ordercode{{$x}}"></svg>
                                            <span style="display: flex; justify-content: space-evenly">
                                                <span id="g_cla" >{{$da['pro_name']}}</span>
                                                <span id="g_cla">Price: {{$da['pro_price']}} TK</span>
                                            </span>
                                            <span style="display: flex; justify-content: space-evenly">
                                                  @if($da['pro_mf_date'])
                                                    <span id="g_cla">MF:{{$da['pro_mf_date']}}</span>
                                                @endif
                                                @if($da['pro_exp_date'])
                                                    <span id="g_cla">EXP:{{$da['pro_exp_date']}}</span>
                                                @endif
                                            </span>
                                        </span>

                                            @php
                                                $x++;
                                            @endphp
                                        @endfor
                                    </div>

                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content Area End -->
        @endsection


        @section('scripts')
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.3/dist/JsBarcode.all.min.js"></script>
            <script>
                $('#product_select').on('change',function () {
                    var productId = $('#product_select').find(":selected").val();
                    var productName = $('#product_select').find(":selected").html();
                    var productExpDate = $('#product_select').find(":selected").attr('exp_date');
                    var productMfDate = $('#product_select').find(":selected").attr('mf_date');
                    var productPrice = $('#product_select').find(":selected").attr('price');
                    $('#result_show').append(' <div class="row">\n' +
                        '                                    <div class="col-lg-6">\n' +
                        '                                        <input readonly type="text" name="pro_name[]" class="input-field" value="\n'+ productName+'">\n' +
                        '                                    </div>\n' +
                        '                                   \n' +
                        '                                        <input type="hidden"  name="pro_id[]" value="\n'+ productId+'">\n' +
                        '                                        <input type="hidden"  name="pro_price[]" value="\n'+ productPrice+'">\n' +
                        '                                        <input type="hidden"  name="pro_mf_date[]" value="\n'+ productMfDate+'">\n' +
                        '                                        <input type="hidden" name="pro_exp_date[]" value="\n'+ productExpDate+'">\n' +
                        '                                    \n' +
                        '                                    <div class="col-lg-4">\n' +
                        '                                        <input type="text" name="quantity[]" class="input-field" placeholder="Quantity" value="1">\n' +
                        '                                    </div>\n' +
                        '                                    <div class="col-lg-2">\n' +
                        '                                        <span id="row_remove" style="color:red" class="remove"><i class="fas fa-times"></i></span>\n' +
                        '                                    </div>\n' +
                        '                                </div>');

                });
                $('#result_show').on("click","#row_remove", function(e){
                    e.preventDefault();
                    $(this).parent('div').parent('div').remove();
                    //x--;
                });

                var product_barcode = <?php echo json_encode($data); ?>;
                //console.log(product_barcode);

                var c = 0;
                for (var i =0 ; i< product_barcode.length; i++){
                    //console.log(product_barcode[i].quantity);
                    for (var j =0 ; j< product_barcode[i].quantity; j++) {
                        JsBarcode('#ordercode'+c, product_barcode[i].id, {
                            format: "CODE39",
                            width: 1.7,
                            height: 30,
                            displayValue: false
                        });
                        c++;
                    }
                }

                function printDiv(){
                    var printContents = document.getElementById("printarea").innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                }



            </script>

@endsection