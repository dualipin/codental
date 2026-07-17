@extends('app')

@section('content')
    <div class="drawer lg:drawer-open flex-1">
        <input id="app-drawer" type="checkbox" class="drawer-toggle"/>

        <div class="drawer-content flex flex-col">
            @include('layouts.partials.app-header')

            <main class="flex-1 p-4 lg:p-6">
                <div class="container">
                    @inertia
                </div>
            </main>
        </div>

        @include('layouts.partials.app-sidebar')
    </div>
@endsection
