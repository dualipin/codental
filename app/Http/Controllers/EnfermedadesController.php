<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;   
use App\Models\Enfermedade;

class EnfermedadesController extends Controller
{
    public function index()
    {
        // Aquí puedes obtener todas las enfermedades de la base de datos
        $enfermedades = Enfermedade::all();

        // Retornar la vista con las enfermedades
        return view('enfermedades.index', compact('enfermedades'));
    }
    //
}
