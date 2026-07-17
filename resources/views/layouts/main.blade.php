@extends('layouts.base')

@section('head')
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/main.ts'])
    @endif
@endsection


@section('body')
    @yield('content')
@endsection
