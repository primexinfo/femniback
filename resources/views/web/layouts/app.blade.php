<!DOCTYPE html>
<html lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @if(isset($page->meta_tag) && isset($page->meta_description))
  <meta name="keywords" content="{{ $page->meta_tag }}">
  <meta name="description" content="{{ $page->meta_description }}">
  <title>{{$gs->title}}</title>
  @elseif(isset($blog->meta_tag) && isset($blog->meta_description))
  <meta name="keywords" content="{{ $blog->meta_tag }}">
  <meta name="description" content="{{ $blog->meta_description }}">
  <title>{{$gs->title}}</title>
  @else
  <meta name="keywords" content="{{ $seo->meta_keys }}">
  <meta name="author" content="Primex Infosys">
  <title>@yield('title')</title>
  @endif

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700" rel="stylesheet">

  <link rel="stylesheet" href="{{asset('web/css/open-iconic-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/animate.css')}}">
  
  <link rel="stylesheet" href="{{asset('web/css/owl.carousel.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/owl.theme.default.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/magnific-popup.css')}}">

  <link rel="stylesheet" href="{{asset('web/css/aos.css')}}">

  <link rel="stylesheet" href="{{asset('web/css/ionicons.min.css')}}">

  <link rel="stylesheet" href="{{asset('web/css/bootstrap-datepicker.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/jquery.timepicker.css')}}">

  
  <link rel="stylesheet" href="{{asset('web/css/flaticon.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/icomoon.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/style.css')}}">
  <link rel="icon" href="{{asset('assets/images/short-icon.jpg')}}" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!--Updated CSS-->
  <link rel="stylesheet"
    href="{{ asset('assets/front/css/styles.php?color='.str_replace('#','',$gs->colors).'&amp;'.'header_color='.str_replace('#','',$gs->header_color).'&amp;'.'footer_color='.str_replace('#','',$gs->footer_color).'&amp;'.'copyright_color='.str_replace('#','',$gs->copyright_color).'&amp;'.'menu_color='.str_replace('#','',$gs->menu_color).'&amp;'.'menu_hover_color='.str_replace('#','',$gs->menu_hover_color)) }}">
</head>
  <body>
    
	  @include('web.include.navbar')
    <!-- END nav -->

    @yield('content')


    <footer class="ftco-footer ftco-bg-dark ftco-section" style="background-color: {{$gs->footer_color}};">
      <div class="container">
        <div class="row mb-2">
          <div class="col-md-9">
            <div class="ftco-footer-widget">
              <h2 class="ftco-heading-2">24/7 EMERGENCY NUMBER (+880 1720-834878)</h2>
              <p>Call us now if you are in a medical emergency need, we will reply swiftly and provide you with a medical aid.</p>
            </div>
            <ul class="ftco-footer-social list-unstyled float-md-left float-lft ">
              <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
            </ul>
          </div>
          <div class="col-md-3">
            <div class="ftco-footer-widget">
            	<h2 class="ftco-heading-2">Chamber</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-home"></span><span class="text">Victoria Heathcare Ltd
</span></li>
	                <li><span class="icon icon-map-marker"></span><span class="text">Block B, 22/2 Babar Rd, Dhaka 1207</span></li>
	                <!-- <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@primex-bd.com</span></a></li> -->
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved <a href="https://primex-bd.com" target="_blank" style="color: #ef4e23;">Primex Information System Ltd.</a></p>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

  <!-- Modal -->
  <div class="modal fade" id="modalRequest" tabindex="-1" role="dialog" aria-labelledby="modalRequestLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header ">
          <h5 class="modal-title" id="modalRequestLabel">Make an Appointment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('set_appoint')}}" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group">
              <!-- <label for="appointment_name" class="text-black">Full Name</label> -->
              <input type="text" class="form-control" name="appointment_name" id="appointment_name" placeholder="Full Name" required>
            </div>
            <!-- <div class="form-group">
              <input type="text" class="form-control" id="appointment_email" placeholder="Email">
            </div> -->
            <div class="form-group">
              <input type="text" class="form-control" name="phone" id="appointment_phone" placeholder="Input Phone Number" required>
            </div>
            <div class="form-group">
                <select name="doctor_id" id="" class="form-control" required>
                  <option selected="false" disabled>Select Doctor</option>
                  <option value="1">Ashraful Haque</option>
                </select>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <!-- <label for="appointment_date" class="text-black">Date</label> -->
                  <input type="text" name="appointment_date" class="form-control appointment_date" placeholder="Date" autocomplete="off" required>
                </div>    
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <!-- <label for="appointment_time" class="text-black">Time</label> -->
                  <input type="text" name="appointment_time" class="form-control appointment_time" placeholder="Time" autocomplete="off" required>
                </div>
              </div>
            </div>
            <div class="form-group float-right">
              <input type="submit" value="Make an Appointment" class="btn btn-primary">
            </div>
          </form>
        </div>
        
      </div>
    </div>
  </div>


  <script src="{{asset('web/js/jquery.min.js')}}"></script>
  <script src="{{asset('web/js/jquery-migrate-3.0.1.min.js')}}"></script>
  <script src="{{asset('web/js/popper.min.js')}}"></script>
  <script src="{{asset('web/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('web/js/jquery.easing.1.3.js')}}"></script>
  <script src="{{asset('web/js/jquery.waypoints.min.js')}}"></script>
  <script src="{{asset('web/js/jquery.stellar.min.js')}}"></script>
  <script src="{{asset('web/js/owl.carousel.min.js')}}"></script>
  <script src="{{asset('web/js/jquery.magnific-popup.min.js')}}"></script>
  <script src="{{asset('web/js/aos.js')}}"></script>
  <script src="{{asset('web/js/jquery.animateNumber.min.js')}}"></script>
  <script src="{{asset('web/js/bootstrap-datepicker.js')}}"></script>
  <script src="{{asset('web/js/jquery.timepicker.min.js')}}"></script>
  <script src="{{asset('web/js/scrollax.min.js')}}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="{{asset('web/js/google-map.js')}}"></script>
  <script src="{{asset('web/js/main.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script>
  @if(Session::has('message'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.error("{{ session('error') }}");
  @endif


  @if(Session::has('info'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.warning("{{ session('warning') }}");
  @endif

    @if($errors->any())
      @foreach($errors->all() as $error)
      toastr.error("{{ $error }}");
      @endforeach
    @endif
</script>
  </body>
</html>