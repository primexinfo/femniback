@if($ps->slider == 1)
<section class="home-slider owl-carousel">
  @if(count($sliders))
  @foreach($sliders as $data)
    <div class="slider-item" style="background-image: url({{asset('assets/images/sliders/'.$data->photo)}});">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text align-items-center" data-scrollax-parent="true">
          <div class="col-md-6 col-sm-12 ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
            <h1 class="mb-4" style="color: {{$data->title_color}}; font-size: {{$data->title_size}}px !important;" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">{{$data->title_text}}</h1>
            <p class="mb-4" style="color: {{$data->details_color}}; font-size: {{$data->details_size}}px !important;" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">{{$data->details_text}}</p>
            <!-- <p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><a href="#" class="btn btn-primary px-4 py-3">Make an Appointment</a></p> -->
          </div>
        </div>
      </div>
    </div>
  @endforeach
  @endif
</section>
@endif
