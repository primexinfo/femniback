@extends('web.layouts.app')
@section('title', $blog->title)

@push('css')
@endpush

@section('content')
    <section class="ftco-section">
      <div class="container">
        <div class="row">
          <div class="col-md-8 ftco-animate">
            <h2 class="mb-1">{{ $blog->title }}</h2>
            <div class="meta mb-2">
              <div><span class="icon-calendar"></span> {{date('d M Y', strtotime($blog->created_at))}} <span class="icon-eye"> {{$blog->views}}</span></div>
            </div>
            <p>{!! $blog->details !!}</p>
            <iframe width="699" height="393" src="https://www.youtube.com/embed/{{$blog->video_link}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>    
              
          </div> <!-- .col-md-8 -->
          <div class="col-md-4 sidebar ftco-animate">
            <div class="sidebar-box">
              <form action="{{route('front.blogsearch')}}" method="GET" class="search-form">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                  <div class="icon"><span class="icon-search"></span></div>
                  <input type="text" name="search" class="form-control" placeholder="Type a keyword and hit enter">
                </div>
              </form>
            </div>
            <div class="sidebar-box ftco-animate">
              <div class="categories">
                <h3>Categories</h3>
                @foreach($bcats as $cat)
                <li><a href="javascript:void(0)">{{ $cat->name }} <span>({{ $cat->blogs()->count() }})</span></a></li>
                @endforeach
              </div>
            </div>
            <div class="sidebar-box ftco-animate">
              <h3>Recent Blog</h3>
              @foreach (App\Models\Blog::orderBy('created_at', 'desc')->limit(4)->get() as $blog)
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" href="{{route('front.blogshow',$blog->id)}}">
                  <img class="w-100" src="{{ $blog->photo ? asset('assets/images/blogs/'.$blog->photo):asset('assets/images/noimage.png') }}">
                </a>
                <div class="text">
                  <h3 class="heading"><a href="{{route('front.blogshow',$blog->id)}}">{{$blog->title}}</a></h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> {{date('d M Y', strtotime($blog->created_at))}}</a></div>
                    <!-- <div><a href="#"><span class="icon-person"></span> Admin</a></div> -->
                    <div><a href="#"><span class="icon-eye"></span> {{$blog->views}}</a></div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@push('css')
@endpush
		
		
