@if($ps->service == 1)
<section class="ftco-section ftco-services">
      <div class="container">
        <div class="row justify-content-center">
          @if($services)
          @foreach($services as $service)
            <div class="col-md-3">
            	<a href="{{route('front.serviceshow',$service->id)}}">
                <img src="{{ $service->photo ? asset('assets/images/services/'.$service->photo):asset('assets/images/noimage.png') }}" style="width: 100%; border-radius: 5px 5px 0px 0px; height: 190px;">
              </a>
              <div class="text-center" style="background-color: {{$service->bg_color}}">
                <div class="media-body">
                  <a href="{{route('front.serviceshow',$service->id)}}">
                    <h3 class="text-white p-1" style="font-size: 24px;">{{substr($service->title,0,18)}}</h3>
                  </a>
                  <p class="text-center text-white pl-2 pr-2 pb-4" style="font-size: 13px;
      line-height: inherit;">{{substr($service->details,0,100)}}</p>
                </div>
                <a href="{{route('front.serviceshow',$service->id)}}" class="btn btn-sm btn-white mb-4"> Read more</a>
              </div>      
            </div>
          @endforeach
          @endif
        </div>
      </div>
      <div class="container-wrap mt-3 mb-4">
      	<div class="row d-flex no-gutters img-fluid service-image">
      		<!-- <div class="col-md-4 img"></div> -->
      		<div class="col-md-12 d-flex">
      			<div class="about-wrap" style="height: 700px;">
      				<div class="list-services d-flex ftco-animate mt-5 pro-about">
      					<!-- <div class="icon d-flex justify-content-center align-items-center">
      					</div> -->
      					<div class="text">
	      					<p class="text-bold" style="margin-bottom: -9px;"> Hi, I am DR. Asraful Hoque Sium</p>
                  <div style="width: 70px;">
                    <hr style="border-top: 2px solid #2bb673;"> 
                  </div>
                  <h2 class="text-bold" style="font-family: Montserrat; color: #2bb673; margin-bottom: -7px;">Heart surgeon</h2>
                  <h5 style="font-family: Montserrat;">MICS specialist</h5>
  	      					<p style="text-align: justify;">Currently practicing as Associate professor and unit chief of cardiac surgery at the National Institute of cardiovascular diseases the one and only Government institute of Bangladesh.I was born and live in Dhaka One of the fast-growing and crowded megacity in the world. I had my graduation from Bangladesh medical college and post-graduation from the National Institute of Cardiovascular Diseases under the University of Dhaka.I m a heart surgeon; always love to do work with the heart and try to keep the heart beating by listening to your heart.i love to take challenges and try to do something new…
                    </p>
                    <h3 class="text-bold d-flex justify-content-end" style="font-family: Montserrat; color: #2bb673;">ASRAFUL HOQUE SIUM</h3>
                    <p class="text-bold d-flex justify-content-end" style="font-family: Montserrat;">Chief Cardiac Surgeon</p>
      					</div>
      				</div>
      			</div>
      		</div>
      	</div>
    </div>
</section>
@endif