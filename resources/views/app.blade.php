@extends('layouts.base')

@section('head')
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @inertiaHead
@endsection

@section('body')
    @yield('content')
@endsection
