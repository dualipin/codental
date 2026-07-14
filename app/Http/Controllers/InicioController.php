<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    //funcion para mostrar la vista de inicio
    public function agenda()
{
    // El Provider ya se encarga del nombre
    return view('agenda.index'); 
}
}
