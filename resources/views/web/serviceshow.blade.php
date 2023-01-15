@extends('web.layouts.app')
@section('title', $service->title)

@push('css')
@endpush

@section('content')
    <section class="ftco-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 ftco-animate">
            <h2 class="mb-1">{{ $service->title }}</h2>
            <div class="meta mb-2">
              <!-- <div><span class="icon-calendar"></span> {{date('d M Y', strtotime($service->created_at))}} <span class="icon-eye"> {{$service->views}}</span></div> -->
            </div>
            <p>{!! $service->details !!}</p>        
              
          </div> <!-- .col-md-8 -->
        </div>
      </div>
    </section>
@endsection

@push('css')
@endpush