<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class WelcomeController extends Controller
{
    //
    public function index()
    {
        return view('nosotros');
    }

    public function test()
    {
        return Inertia::render('Hello');
    }
}
