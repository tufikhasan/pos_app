@extends('layouts.frontend')
@section('site_title', '404 NOT FOUND')
@section('content')
    <div class=" bg-white h-100vh w-100 d-flex align-items-center justify-content-center flex-column">
        <img src="{{ asset('assets/img/404.jpg') }}" class="h-auto" width="300">
        <a class="btn btn-primary" href="{{ route('dashboard') }}">
            <<< Go Back Home Page</a>
    </div>
@endsection
@section('script')

@endsection
