<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Paciente;
use Inertia\Inertia;

class DashboardRecepcionController extends Controller
{
    public function index()
    {
        $followUpPatients = Paciente::needsFollowUp()->with(['citas' => function($q) {
            $q->orderBy('fecha_inicio', 'desc');
        }])->get();

        return Inertia::render('Reception/Dashboard', [
            'followUpPatients' => $followUpPatients
        ]);
    }
}
