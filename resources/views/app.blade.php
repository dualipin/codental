<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="main">
<head>
    <title>{{ config('app.name', 'CoDental') }}</title>
    @include('layouts.partials.head')
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.ts',  "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead

</head>
<body class="min-h-dvh flex flex-col bg-base-200 font-sans text-base-content antialiased">
@yield('content')
@inertia
</body>
</html>
