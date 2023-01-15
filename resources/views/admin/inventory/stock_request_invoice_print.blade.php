<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{{$seo->meta_keys}}">
    <meta name="author" content="GeniusOcean">

    <title>{{$gs->title}}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('assets/print/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/print/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('assets/print/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/print/css/style.css')}}">
    <link href="{{asset('assets/print/css/print.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" type="image/png" href="{{asset('assets/images/'.$gs->favicon)}}">
    <style type="text/css">
        @page { size: auto;  margin: 0mm; }
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 287mm;
            }

            html {

            }

            ::-webkit-scrollbar {
                width: 0px; /* remove scrollbar space */
                background: transparent; /* optional: just make scrollbar invisible */
            }
        }
    </style>
</head>
<body onload="window.print();">
<div class="invoice-wrap">
    <div class="invoice__title">
        <div class="row">
            <div class="col-sm-6">
                <div class="invoice__logo text-left">
                    <img src="{{ asset('assets/images/'.$gs->invoice_logo) }}" alt="woo commerce logo">
                </div>
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
                <span style="display: flex"><strong style="margin-top: 9px" >{{ __('Barcode') }} :</strong> <svg id="barcode" ></svg></span><br>

            </div>
        </div>
    </div>

    <div class="col-lg-12">
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
<!-- ./wrapper -->
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
    }, 5000);
</script>

</body>
</html>
