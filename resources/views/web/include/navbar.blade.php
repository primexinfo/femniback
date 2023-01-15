<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="/"><img style="height: 63px;" src="{{asset('web/images/Logo.png')}}"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav" style="color: {{$gs->menu_color}} !important;">
        <ul class="navbar-nav ml-auto">
              @if($gs->is_home == 1)
              <li class="nav-item"><a href="{{ route('front.index') }}" class="nav-link">{{ $langg->lang17 }}</a></li>
              @endif
              <li class="nav-item"><a href="{{ route('front.blog') }}" class="nav-link">{{ $langg->lang18 }}</a></li>
              @if($gs->is_faq == 1)
              <li class="nav-item"><a href="{{ route('front.faq') }}" class="nav-link">{{ $langg->lang19 }}</a></li>
              @endif
              @foreach(DB::table('pages')->where('header','=',1)->get() as $data)
              <li class="nav-item">
                <a href="{{ route('front.page', $data->slug) }}" class="nav-link">{{ $data->title }}</a>
              </li>
              @endforeach
              @if($gs->is_contact == 1)
              <li class="nav-item"><a href="{{ route('front.contact') }}" class="nav-link">{{ $langg->lang20 }}</a></li>
              @endif
              <li class="nav-item cta"><a href="javascript:void(0)" class="nav-link" data-toggle="modal" data-target="#modalRequest"><span>Make an Appointment</span></a></li>
          
        </ul>
      </div>
    </div>
</nav>