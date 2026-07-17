<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use Barryvdh\DomPDF\Facade\Pdf;

class PresupuestoPdfController extends Controller
{
    public function download(Presupuesto $presupuesto)
    {
        $presupuesto->load([
            'paciente',
            'dentista',
            'detalles.tratamientoCatalogo',
        ]);

        $pdf = Pdf::loadView('pdf.presupuesto', [
            'presupuesto' => $presupuesto,
        ])->setOptions([
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ]);

        return $pdf->download("presupuesto-{$presupuesto->id}.pdf");
    }
}
