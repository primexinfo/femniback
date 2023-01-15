@extends('web.layouts.app')

@section('title','Blog')

@section('content')
		<section class="ftco-section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							@foreach($blogs as $blogg)
							<div class="col-md-3 ftco-animate">
		            <div class="blog-entry">
		              <a href="{{route('front.blogshow',$blogg->id)}}">
		              	<img class="w-100" style="height: 200px;" src="{{ $blogg->photo ? asset('assets/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png') }}">
		              </a>
		              <div class="text py-4" style="background-color: #ededed;">
		                <div class="desc pl-sm-2 pl-md-3">
			                <h3 class="heading"><a href="{{route('front.blogshow',$blogg->id)}}">{{strlen($blogg->title) > 50 ? substr($blogg->title,0,50)."...":$blogg->title}}</a></h3>
			                <p style="font-size: 13px;">{{substr(strip_tags($blogg->details),0,120)}}</p>
			                <p><a href="{{route('front.blogshow',$blogg->id)}}" class="btn btn-sm btn-primary btn-outline-primary">Read more</a></p>
			              </div>
		              </div>
		            </div>
		          </div>
		          @endforeach
						</div>
						<div class="row mt-5">
		          <div class="col">
		            <div class="block-27">
		               {!! $blogs->links() !!}
		            </div>
		          </div>
		        </div>
					</div>
					<!-- END: col-md-8 -->
					<!-- <div class="col-md-4 sidebar ftco-animate">
            <div class="sidebar-box">
              <form action="{{route('front.blogsearch')}}" method="GET" class="search-form">
              	<input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                  <span class="icon fa fa-search"></span>
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
                	<img class="w-100" src="{{ $blogg->photo ? asset('assets/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png') }}">
                </a>
                <div class="text">
                  <h3 class="heading"><a href="{{route('front.blogshow',$blog->id)}}">{{$blog->title}}</a></h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> {{date('d M Y', strtotime($blog->created_at))}}</a></div>
                    <div><a href="#"><span class="icon-eye"></span> {{$blog->views}}</a></div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div> -->
				</div>
			</div>
		</section>
@endsection