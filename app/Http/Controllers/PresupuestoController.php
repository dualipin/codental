<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PresupuestoService;

class PresupuestoController extends Controller
{
    protected PresupuestoService $presupuestoService;

    public function __construct(PresupuestoService $presupuestoService)
    {
        $this->presupuestoService = $presupuestoService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'detalles' => 'required|array|min:1',
            'detalles.*.tratamiento_catalogo_id' => 'required|exists:tratamiento_catalogos,id',
            'detalles.*.referencia_odontograma_id' => 'nullable|string',
            'detalles.*.monto_descuento' => 'nullable|numeric|min:0',
            'detalles.*.justificacion_descuento' => 'nullable|string',
        ]);

        try {
            $presupuesto = $this->presupuestoService->crearPresupuesto(
                $validated['paciente_id'],
                $request->user()->id ?? 1, // fallback provisorio
                $validated['detalles']
            );

            return response()->json(['message' => 'Presupuesto creado con éxito.', 'data' => $presupuesto], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
