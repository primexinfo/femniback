@extends('layouts.admin')

@section('content')
<div class="content-area">
    @include('includes.form-success')

    <div class="row row-cards-one">
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg2">
                    <div class="left">
                        <h5 class="title">{{ __('Users') }}</h5>
                        <span class="number">{{count($users)}}</span>
                        
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-user-alt-5"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg3">
                    <div class="left">
                        <h5 class="title">{{ __('Blogs') }}</h5>
                        <span class="number">{{count($blogs)}}</span>
                        
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-blogger"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg4">
                    <div class="left">
                        <h5 class="title">{{ __('Appointments') }}</h5>
                        <span class="number">{{count($appoints)}}</span>
                        
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg5">
                    <div class="left">
                        <h5 class="title">{{ __('Subscribers') }}</h5>
                        <span class="number">{{count($subscribers)}}</span>
                        
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg6">
                    <div class="left">
                        <h5 class="title">{{ __('Testimonials') }}</h5>
                        <span class="number">{{count($testimonials)}}</span>
                        
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-speech-comments"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg1">
                    <div class="left">
                        <h5 class="title">{{ __('Services') }}</h5>
                        <span class="number">{{count($services)}}</span>
                        
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-speech-comments"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

</div>

@endsection

@section('scripts')

<script language="JavaScript">
    displayLineChart();

    function displayLineChart() {
        var data = {
            labels: [
            {!!$days!!}
            ],
            datasets: [{
                label: "Prime and Fibonacci",
                fillColor: "#3dbcff",
                strokeColor: "#0099ff",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [
                {!!$sales!!}
                ]
            }]
        };
        var ctx = document.getElementById("lineChart").getContext("2d");
        var options = {
            responsive: true
        };
        var lineChart = new Chart(ctx).Line(data, options);
    }


    
</script>

<script type="text/javascript">
    $('#poproducts').dataTable( {
      "ordering": false,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          'autoWidth'   : false,
          'responsive'  : true,
          'paging'  : false
    } );
    </script>


<script type="text/javascript">
    $('#pproducts').dataTable( {
      "ordering": false,
      'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          'autoWidth'   : false,
          'responsive'  : true,
          'paging'  : false
    } );
    </script>

<script type="text/javascript">
        var chart1 = new CanvasJS.Chart("chartContainer-topReference",
            {
                exportEnabled: true,
                animationEnabled: true,

                legend: {
                    cursor: "pointer",
                    horizontalAlign: "right",
                    verticalAlign: "center",
                    fontSize: 16,
                    padding: {
                        top: 20,
                        bottom: 2,
                        right: 20,
                    },
                },
                data: [
                    {
                        type: "pie",
                        showInLegend: true,
                        legendText: "",
                        toolTipContent: "{name}: <strong>{#percent%} (#percent%)</strong>",
                        indexLabel: "#percent%",
                        indexLabelFontColor: "white",
                        indexLabelPlacement: "inside",
                        dataPoints: [
                                @foreach($referrals as $browser)
                                    {y:{{$browser->total_count}}, name: "{{$browser->referral}}"},
                                @endforeach
                        ]
                    }
                ]
            });
        chart1.render();

        var chart = new CanvasJS.Chart("chartContainer-os",
            {
                exportEnabled: true,
                animationEnabled: true,
                legend: {
                    cursor: "pointer",
                    horizontalAlign: "right",
                    verticalAlign: "center",
                    fontSize: 16,
                    padding: {
                        top: 20,
                        bottom: 2,
                        right: 20,
                    },
                },
                data: [
                    {
                        type: "pie",
                        showInLegend: true,
                        legendText: "",
                        toolTipContent: "{name}: <strong>{#percent%} (#percent%)</strong>",
                        indexLabel: "#percent%",
                        indexLabelFontColor: "white",
                        indexLabelPlacement: "inside",
                        dataPoints: [
                            @foreach($browsers as $browser)
                                {y:{{$browser->total_count}}, name: "{{$browser->referral}}"},
                            @endforeach
                        ]
                    }
                ]
            });
        chart.render();    
</script>

@endsection