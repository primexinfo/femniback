@extends('web.layouts.app')
@section('title','Team Asraf Sium: - : Listen to your Heart')

@push('css')
@endpush

@section('content')
    <!-- slider -->
    @if($ps->slider == 1)
        @if(count($sliders))
            @include('includes.slider-style')
        @endif
    @endif
    @include('web.include.slider')
    <!-- Appoint -->
    @include('web.include.appoint')


    <!-- Services -->
    @include('web.include.services')


    @include('web.include.testimonial')

    @include('web.include.blog')
@endsection

@push('css')
@endpush