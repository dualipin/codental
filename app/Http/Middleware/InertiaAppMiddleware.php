<?php

namespace App\Http\Middleware;


use Inertia\Middleware;

class InertiaAppMiddleware extends Middleware
{
    protected $rootView = 'layouts.app-inertia';
}
