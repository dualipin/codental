<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowPacienteController extends Controller
{
    //
    public function index()
    {
        return view('pacientes.show_pacientes');
    }
    public function create()
    {
        return view('pacientes.regispacientes');
    }
}
