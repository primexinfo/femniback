@extends('web.layouts.app')
@section('title', $page->title)

@push('css')
@endpush

@section('content')
    <section class="ftco-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 ftco-animate">
            <h2 class="mb-1">{{ $page->title }}</h2>
            <p>{!! $page->details !!}</p>        
              
          </div> <!-- .col-md-8 -->
        </div>
      </div>
    </section>
@endsection

@push('css')
@endpush
		
		
