<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cita;
use App\Enums\EstatusCitaEnum;
use Illuminate\Support\Facades\DB;

class EvolucionClinicaController extends Controller
{
    public function store(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'subjetivo' => 'nullable|string',
            'objetivo' => 'nullable|string',
            'analisis' => 'nullable|string',
            'plan' => 'nullable|string',
            'tratamientos_completados' => 'nullable|array',
            'recetas' => 'nullable|array',
        ]);

        DB::transaction(function () use ($validated, $cita) {
            $cita->evolucionClinica()->create([
                'paciente_id' => $cita->paciente_id,
                'odontologo_id' => auth()->id() ?? $cita->dentista_id,
                'subjetivo' => $validated['subjetivo'] ?? null,
                'objetivo' => $validated['objetivo'] ?? null,
                'analisis' => $validated['analisis'] ?? null,
                'plan' => $validated['plan'] ?? null,
                'tratamientos_completados' => $validated['tratamientos_completados'] ?? null,
            ]);

            if (!empty($validated['recetas'])) {
                $receta = $cita->receta()->create();
                $receta->detalles()->createMany($validated['recetas']);
            }

            if (!empty($validated['tratamientos_completados'])) {
                DB::table('tratamientos_aplicados')
                    ->whereIn('id', $validated['tratamientos_completados'])
                    ->update(['estado' => 'completado']);
            }

            $cita->update(['estatus' => EstatusCitaEnum::FINALIZADO->value]);
        });

        return redirect()->back()->with('success', 'Evolución clínica guardada exitosamente.');
    }
}
