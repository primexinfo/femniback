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
                width: 0px;  /* remove scrollbar space */
                background: transparent;  /* optional: just make scrollbar invisible */
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
</div>

<div class="col-lg-12">
    <div class="invoice_table">
        <div class="mr-table">
            <div class="row">
                <span>{{$products->name}}</span>
                <span >{{$products->price}}</span>
            </div>
            <div class="table-responsive">
                <svg id="barcode" ></svg>
            </div>
        </div>
    </div>
</div>



<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.3/dist/JsBarcode.all.min.js"></script>
<script type="text/javascript">


    JsBarcode("#barcode", "{{$products->id}}", {
        format: "code39",
        width: 2,
        height: 30,
        displayValue: true,
        font:	"monospace",
        fontSize:	14,
    });
    // setTimeout(function () {
    //     window.close();
    // }, 500);

</script>

</body>
</html>
