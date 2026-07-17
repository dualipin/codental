<!DOCTYPE html>
<html lang="es" data-theme="main">
<head>
    <title>{{ config('app.name', 'CoDental') }}</title>
    @include('layouts.partials.head')
    @yield('head')
</head>
<body class="min-h-dvh flex flex-col bg-base-200 font-sans text-base-content antialiased">
@yield('body')
</body>
</html>
