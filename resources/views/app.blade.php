@extends('layouts.base')

@section('head')
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.ts',  "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
@endsection

@section('body')
    @yield('content')
@endsection
