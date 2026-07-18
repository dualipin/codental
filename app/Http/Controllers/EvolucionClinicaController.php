<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cita;
use App\Models\EvolucionClinica;
use App\Enums\EstatusCitaEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Paciente;
use Inertia\Inertia;

class EvolucionClinicaController extends Controller
{
    public function consultaExpress(Paciente $paciente)
    {
        $usuario = auth()->user();

        $cita = Cita::create([
            'paciente_id' => $paciente->id,
            'dentista_id' => $usuario->id,
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addMinutes(30),
            'motivo' => 'Consulta Express (Sin cita previa)',
            'estatus' => EstatusCitaEnum::PENDIENTE->value,
        ]);

        return redirect()->route('evolucion.create', ['cita' => $cita->id]);
    }

    public function create(Cita $cita)
    {
        $tratamientosPlanificados = DB::table('tratamientos_aplicados')
            ->where('paciente_id', $cita->paciente_id)
            ->whereIn('estado', ['planificado', 'en_proceso'])
            ->join('tratamientos', 'tratamientos_aplicados.tratamiento_id', '=', 'tratamientos.id')
            ->join('dientes', 'tratamientos_aplicados.diente_id', '=', 'dientes.id')
            ->leftJoin('caras_dentales', 'tratamientos_aplicados.cara_dental_id', '=', 'caras_dentales.id')
            ->select(
                'tratamientos_aplicados.id',
                'tratamientos.nombre as nombre_tratamiento',
                'dientes.numero_fdi as diente_numero',
                'caras_dentales.nombre as cara_nombre'
            )
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'nombre' => $t->nombre_tratamiento,
                'diente' => 'Diente #' . $t->diente_numero,
                'cara' => $t->cara_nombre ?? '—',
            ]);

        $cita->load(['paciente', 'dentista']);

        return Inertia::render('Doctor/EvolutionForm', [
            'citaId' => $cita->id,
            'cita' => [
                'fecha_inicio' => $cita->fecha_inicio->toIso8601String(),
                'paciente' => [
                    'nombre' => $cita->paciente->nombre,
                    'apellido_paterno' => $cita->paciente->apellido_paterno,
                    'apellido_materno' => $cita->paciente->apellido_materno,
                ],
                'dentista' => [
                    'nombre' => $cita->dentista->nombre,
                    'apellido_paterno' => $cita->dentista->apellido_paterno,
                    'apellido_materno' => $cita->dentista->apellido_materno,
                ],
            ],
            'tratamientosPlanificados' => $tratamientosPlanificados,
        ]);
    }

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

        $evolucion = DB::transaction(function () use ($validated, $cita) {
            $evolucion = $cita->evolucionClinica()->create([
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
                    ->update(['estado' => 'completado', 'fecha_realizado' => now()]);
            }

            $cita->update(['estatus' => EstatusCitaEnum::FINALIZADO->value]);

            return $evolucion;
        });

        return redirect()->route('agenda.citas.confirmar', ['cita' => $cita->id])
            ->with('success', 'Evolución clínica guardada exitosamente.')
            ->with('evolucion_id', $evolucion->id);
    }

    public function downloadPdf(EvolucionClinica $evolucion)
    {
        $evolucion->load([
            'odontologo',
            'cita.paciente',
            'cita.dentista',
            'cita.receta.detalles',
        ]);

        $pdf = Pdf::loadView('pdf.evolucion-clinica', compact('evolucion'))
            ->setOptions([
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        return $pdf->stream("evolucion_{$evolucion->id}.pdf");
    }
}
