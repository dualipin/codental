<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CajaService;

class CajaController extends Controller
{
    protected CajaService $cajaService;

    public function __construct(CajaService $cajaService)
    {
        $this->cajaService = $cajaService;
    }

    public function registrarAbono(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,mixto',
            'referencia_bancaria' => 'nullable|string',
            'distribucion' => 'required|array|min:1',
            'distribucion.*.presupuesto_detalle_id' => 'required|exists:presupuesto_detalles,id',
            'distribucion.*.monto_aplicado' => 'required|numeric|min:0.01',
        ]);

        try {
            $movimiento = $this->cajaService->registrarPago(
                $validated['paciente_id'],
                $request->user()->id ?? 1, // fallback provisorio
                $validated['monto'],
                $validated['metodo_pago'],
                $validated['referencia_bancaria'],
                $validated['distribucion']
            );

            return response()->json(['message' => 'Pago registrado correctamente.', 'data' => $movimiento], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function anularAbono(Request $request, $movimientoId)
    {
        try {
            $anulacion = $this->cajaService->anularPago($movimientoId, $request->user()->id ?? 1);
            return response()->json(['message' => 'Pago anulado correctamente.', 'data' => $anulacion], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function estadoCuenta(Request $request, $pacienteId)
    {
        $saldo = $this->cajaService->calcularSaldoPaciente($pacienteId);
        return response()->json(['paciente_id' => $pacienteId, 'saldo_actual' => $saldo], 200);
    }
}
