<?php

namespace App\Http\Controllers;

use App\Enums\TipoSeguimientoOdontogramaEnum;
use App\Models\CarasDentales;
use App\Models\Diente;
use App\Models\Enfermedad;
use App\Models\HallazgoDental;
use App\Models\Odontograma;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OdontogramaController extends Controller
{
    public function inicial(Paciente $paciente): Response
    {
        return $this->renderOdontograma($paciente, 'inicial');
    }

    public function final(Paciente $paciente): Response
    {
        return $this->renderOdontograma($paciente, 'final');
    }

    private function renderOdontograma(Paciente $paciente, string $vista): Response
    {
        $enfermedades = Enfermedad::orderBy('nombre')->get();
        $dientes = Diente::orderBy('numero_fdi')->get();
        $caras = CarasDentales::orderBy('id')->get();

        $odontogramaInicial = Odontograma::with(['hallazgos.enfermedad', 'hallazgos.diente', 'hallazgos.caraDental'])
            ->where('paciente_id', $paciente->id)
            ->where('tipo_seguimiento', TipoSeguimientoOdontogramaEnum::EVALUACION_INICIAL)
            ->first();

        $odontogramaFinal = Odontograma::with(['hallazgos.enfermedad', 'hallazgos.diente', 'hallazgos.caraDental'])
            ->where('paciente_id', $paciente->id)
            ->whereIn('tipo_seguimiento', [
                TipoSeguimientoOdontogramaEnum::SEGUIMIENTO,
                TipoSeguimientoOdontogramaEnum::ALTA,
                TipoSeguimientoOdontogramaEnum::REEVALUACION,
            ])
            ->latest('fecha')
            ->first();

        return Inertia::render('Pacientes/Odontograma/' . ucfirst($vista), [
            'paciente' => [
                'id' => $paciente->id,
                'nombre' => $paciente->nombre,
                'apellido_paterno' => $paciente->apellido_paterno,
                'apellido_materno' => $paciente->apellido_materno,
            ],
            'catalogoEnfermedades' => $enfermedades,
            'catalogoCaras' => $caras->map(fn (CarasDentales $cara) => [
                'id' => $cara->id,
                'nombre' => $cara->nombre,
                'codigo' => $cara->codigo,
            ]),
            'dientes' => $dientes,
            'inicial' => $this->mapOdontograma($odontogramaInicial),
            'final' => $this->mapOdontograma($odontogramaFinal),
        ]);
    }

    private function mapOdontograma(?Odontograma $odontograma): ?array
    {
        if (! $odontograma) {
            return null;
        }

        return [
            'id' => $odontograma->id,
            'tipo' => $odontograma->tipo_seguimiento->value,
            'observaciones' => $odontograma->observaciones,
            'hallazgos' => $odontograma->hallazgos->map(fn (HallazgoDental $hallazgo) => [
                'id' => $hallazgo->id,
                'diente' => $hallazgo->diente->numero_fdi,
                'cara' => $hallazgo->caraDental?->codigo ?? 'C',
                'enfermedad_id' => $hallazgo->enfermedad_id,
                'notas' => $hallazgo->notas ?? '',
                'estado' => $this->mapearEstado($hallazgo->estado),
                'en_plan' => (bool) $hallazgo->en_plan,
            ]),
        ];
    }

    private function mapearEstado(string $estado): string
    {
        return match ($estado) {
            'resuelto' => 'RESUELTO',
            'descartado' => 'DESCARTADO',
            default => 'ACTIVO',
        };
    }

    public function guardar(Request $request, Paciente $paciente)
    {
        $request->validate([
            'vista' => ['required', 'in:inicial,final'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            'hallazgos' => ['required', 'array'],
            'hallazgos.*.diente' => ['required', 'integer', 'exists:dientes,numero_fdi'],
            'hallazgos.*.cara' => ['required', 'string', 'in:V,L,M,D,O,C'],
            'hallazgos.*.enfermedad_id' => ['required', 'integer', 'exists:enfermedades,id'],
            'hallazgos.*.notas' => ['nullable', 'string', 'max:500'],
            'hallazgos.*.estado' => ['required', 'string', 'in:ACTIVO,RESUELTO,DESCARTADO'],
            'hallazgos.*.en_plan' => ['boolean'],
        ]);

        $vista = $request->input('vista');
        $tipo = $vista === 'inicial'
            ? TipoSeguimientoOdontogramaEnum::EVALUACION_INICIAL
            : TipoSeguimientoOdontogramaEnum::SEGUIMIENTO;

        DB::transaction(function () use ($request, $paciente, $tipo) {
            $odontograma = Odontograma::firstOrCreate(
                [
                    'paciente_id' => $paciente->id,
                    'tipo_seguimiento' => $tipo,
                ],
                [
                    'odontologo_id' => auth()->id(),
                    'fecha' => Carbon::today(),
                    'observaciones' => $request->input('observaciones'),
                ]
            );

            $odontograma->update([
                'observaciones' => $request->input('observaciones'),
                'odontologo_id' => auth()->id() ?? $odontograma->odontologo_id,
            ]);

            $odontograma->hallazgos()->delete();

            $carasPorCodigo = CarasDentales::whereIn('codigo', ['V', 'L', 'M', 'D', 'O'])
                ->get()
                ->keyBy('codigo');

            foreach ($request->input('hallazgos') as $item) {
                $diente = Diente::where('numero_fdi', $item['diente'])->first();
                $cara = $item['cara'] === 'C' ? null : $carasPorCodigo->get($item['cara']);

                if (! $diente) {
                    continue;
                }

                HallazgoDental::create([
                    'odontograma_id' => $odontograma->id,
                    'diente_id' => $diente->id,
                    'cara_dental_id' => $cara?->id,
                    'enfermedad_id' => $item['enfermedad_id'],
                    'estado' => strtolower($item['estado']),
                    'en_plan' => $item['en_plan'] ?? false,
                    'notas' => $item['notas'] ?? null,
                ]);
            }
        });

        return redirect()->route("pacientes.odontograma.{$vista}", $paciente)
            ->with('success', 'Odontograma guardado correctamente.');
    }
}
