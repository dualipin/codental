<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Receta;
use Barryvdh\DomPDF\Facade\Pdf;

class RecetaController extends Controller
{
    public function downloadPdf(Receta $receta)
    {
        $receta->load(['cita.paciente', 'cita.dentista', 'detalles']);

        $pdf = Pdf::loadView('pdf.receta', compact('receta'));
        
        return $pdf->download("receta_{$receta->id}.pdf");
    }

    public function streamPdf(Receta $receta)
    {
        $receta->load(['cita.paciente', 'cita.dentista', 'detalles']);

        $pdf = Pdf::loadView('pdf.receta', compact('receta'));
        
        return $pdf->stream("receta_{$receta->id}.pdf");
    }
}
