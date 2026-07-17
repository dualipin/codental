<?php

namespace App\Http\Controllers;

use App\Enums\UserRolEnum;
use App\Models\HistoriaClinica;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

use function auth;
use function redirect;

class HistoriaClinicaController extends Controller
{
    public function edit(Paciente $paciente)
    {
        $this->autorizarEdicion();

        $paciente->load('historiaClinica');

        return Inertia::render('Pacientes/HistoriaClinica/Edit', [
            'paciente' => [
                'id' => $paciente->id,
                'nombre' => $paciente->nombre,
                'apellido_paterno' => $paciente->apellido_paterno,
                'apellido_materno' => $paciente->apellido_materno,
                'sexo' => $paciente->sexo?->value,
            ],
            'historiaClinica' => $this->mapearHistoriaClinica($paciente->historiaClinica),
        ]);
    }

    public function update(Request $request, Paciente $paciente)
    {
        $this->autorizarEdicion();

        $validated = $request->validate([
            'antecedentes_hereditarios' => ['nullable', 'string'],
            'alergias' => ['nullable', 'string'],
            'medicacion_actual' => ['nullable', 'string'],
            'nombre_medico' => ['nullable', 'string', 'max:150'],
            'telefono_medico' => ['nullable', 'string', 'max:20'],
            'enfermedades_previas' => ['nullable', 'array'],
            'enfermedades_previas.*' => ['string', Rule::in($this->opcionesEnfermedad())],
            'otras_enfermedades' => ['nullable', 'string'],
            'habitos_toxicos' => ['nullable', 'array'],
            'habitos_toxicos.tabaco' => ['nullable', 'boolean'],
            'habitos_toxicos.alcohol' => ['nullable', 'boolean'],
            'habitos_toxicos.drogas' => ['nullable', 'boolean'],
            'habitos_toxicos.frecuenciaConsumo' => ['nullable', 'string', 'max:100'],
            'grupo_sanguineo' => ['nullable', 'string', 'max:10'],
            'ginecoobstetricos' => ['nullable', 'array'],
            'ginecoobstetricos.embarazo' => ['nullable', 'boolean'],
            'ginecoobstetricos.tiempoGestacion' => ['nullable', 'string', 'max:100'],
            'ginecoobstetricos.lactancia' => ['nullable', 'boolean'],
            'ginecoobstetricos.mesesBebe' => ['nullable', 'string', 'max:100'],
            'estilo_vida' => ['nullable', 'array'],
            'estilo_vida.actividadFisica' => ['nullable', 'string', 'max:100'],
            'estilo_vida.calidadDieta' => ['nullable', 'string', 'max:100'],
            'estilo_vida.calidadHigiene' => ['nullable', 'string', 'max:100'],
            'cirugias_hospitalizaciones' => ['nullable', 'string'],
            'padecimiento_actual' => ['nullable', 'string'],
            'interrogatorio_sistemas' => ['nullable', 'string'],
            'examenes_laboratorio' => ['nullable', 'string'],
            'antecedentes_bucodentales' => ['nullable', 'array'],
            'atm' => ['nullable', 'array'],
            'atm.malestarAbrirBocas' => ['nullable', 'boolean'],
            'atm.malestarMovimientoLateral' => ['nullable', 'boolean'],
            'atm.chasquidosCrepitaciones' => ['nullable', 'boolean'],
            'atm.desviacionMandibula' => ['nullable', 'boolean'],
            'tejidos_blandos_duros' => ['nullable', 'string'],
        ]);

        $habitos = $validated['habitos_toxicos'] ?? [];
        $gineco = $validated['ginecoobstetricos'] ?? [];
        $estiloVida = $validated['estilo_vida'] ?? [];
        $atm = $validated['atm'] ?? [];

        $paciente->historiaClinica()->updateOrCreate(
            ['paciente_id' => $paciente->id],
            [
                'antecedentes_hereditarios' => $this->limpiarTexto($validated['antecedentes_hereditarios'] ?? null),
                'alergias' => $this->limpiarTexto($validated['alergias'] ?? null),
                'medicacion_actual' => $this->limpiarTexto($validated['medicacion_actual'] ?? null),
                'nombre_medico' => $this->limpiarTexto($validated['nombre_medico'] ?? null),
                'telefono_medico' => $this->limpiarTexto($validated['telefono_medico'] ?? null),
                'enfermedades_previas' => array_values($validated['enfermedades_previas'] ?? []),
                'otras_enfermedades' => $this->limpiarTexto($validated['otras_enfermedades'] ?? null),
                'habitos_toxicos' => [
                    'tabaco' => (bool) ($habitos['tabaco'] ?? false),
                    'alcohol' => (bool) ($habitos['alcohol'] ?? false),
                    'drogas' => (bool) ($habitos['drogas'] ?? false),
                    'frecuenciaConsumo' => $this->limpiarTexto($habitos['frecuenciaConsumo'] ?? null) ?? '',
                ],
                'grupo_sanguineo' => $this->limpiarTexto($validated['grupo_sanguineo'] ?? null),
                'ginecoobstetricos' => [
                    'embarazo' => (bool) ($gineco['embarazo'] ?? false),
                    'tiempoGestacion' => $this->limpiarTexto($gineco['tiempoGestacion'] ?? null) ?? '',
                    'lactancia' => (bool) ($gineco['lactancia'] ?? false),
                    'mesesBebe' => $this->limpiarTexto($gineco['mesesBebe'] ?? null) ?? '',
                ],
                'estilo_vida' => [
                    'actividadFisica' => $this->limpiarTexto($estiloVida['actividadFisica'] ?? null) ?? '',
                    'calidadDieta' => $this->limpiarTexto($estiloVida['calidadDieta'] ?? null) ?? '',
                    'calidadHigiene' => $this->limpiarTexto($estiloVida['calidadHigiene'] ?? null) ?? '',
                ],
                'cirugias_hospitalizaciones' => $this->limpiarTexto($validated['cirugias_hospitalizaciones'] ?? null),
                'padecimiento_actual' => $this->limpiarTexto($validated['padecimiento_actual'] ?? null),
                'interrogatorio_sistemas' => $this->limpiarTexto($validated['interrogatorio_sistemas'] ?? null),
                'examenes_laboratorio' => $this->limpiarTexto($validated['examenes_laboratorio'] ?? null),
                'antecedentes_bucodentales' => $validated['antecedentes_bucodentales'] ?? [],
                'atm' => [
                    'malestarAbrirBocas' => (bool) ($atm['malestarAbrirBocas'] ?? false),
                    'malestarMovimientoLateral' => (bool) ($atm['malestarMovimientoLateral'] ?? false),
                    'chasquidosCrepitaciones' => (bool) ($atm['chasquidosCrepitaciones'] ?? false),
                    'desviacionMandibula' => (bool) ($atm['desviacionMandibula'] ?? false),
                ],
                'tejidos_blandos_duros' => $this->limpiarTexto($validated['tejidos_blandos_duros'] ?? null),
            ],
        );

        return redirect()
            ->route('pacientes.historia-clinica.edit', $paciente)
            ->with('success', 'Historia clinica actualizada correctamente.');
    }

    private function autorizarEdicion(): void
    {
        $usuario = auth()->user();
        $rol = $usuario?->rol instanceof UserRolEnum
            ? $usuario->rol->value
            : (string) $usuario?->rol;

        if (! $usuario || ! in_array($rol, [UserRolEnum::DENTISTA->value, UserRolEnum::ADMINISTRADOR->value], true)) {
            abort(403, 'No tienes permisos para editar esta historia clinica.');
        }
    }

    private function mapearHistoriaClinica(?HistoriaClinica $historiaClinica): array
    {
        return [
            'antecedentes_hereditarios' => $historiaClinica?->antecedentes_hereditarios,
            'alergias' => $historiaClinica?->alergias,
            'medicacion_actual' => $historiaClinica?->medicacion_actual,
            'nombre_medico' => $historiaClinica?->nombre_medico,
            'telefono_medico' => $historiaClinica?->telefono_medico,
            'enfermedades_previas' => $historiaClinica?->enfermedades_previas ?? [],
            'otras_enfermedades' => $historiaClinica?->otras_enfermedades,
            'habitos_toxicos' => $historiaClinica?->habitos_toxicos ?? [],
            'grupo_sanguineo' => $historiaClinica?->grupo_sanguineo,
            'ginecoobstetricos' => $historiaClinica?->ginecoobstetricos ?? [],
            'estilo_vida' => $historiaClinica?->estilo_vida ?? [],
            'cirugias_hospitalizaciones' => $historiaClinica?->cirugias_hospitalizaciones,
            'padecimiento_actual' => $historiaClinica?->padecimiento_actual,
            'interrogatorio_sistemas' => $historiaClinica?->interrogatorio_sistemas,
            'examenes_laboratorio' => $historiaClinica?->examenes_laboratorio,
            'antecedentes_bucodentales' => $historiaClinica?->antecedentes_bucodentales ?? [],
            'atm' => $historiaClinica?->atm ?? [],
            'tejidos_blandos_duros' => $historiaClinica?->tejidos_blandos_duros,
        ];
    }

    private function limpiarTexto(?string $valor): ?string
    {
        if ($valor === null) {
            return null;
        }

        $valor = trim($valor);

        return $valor === '' ? null : $valor;
    }

    private function opcionesEnfermedad(): array
    {
        return [
            'diabetes',
            'vih',
            'asma',
            'hipertension',
            'sida',
            'infartos',
            'cancer',
            'vph',
            'epilepsia',
            'enfermedades_mentales',
            'enfermedades_cardiacas',
            'hepatitis',
            'enfermedades_hepaticas',
            'enfermedades_glandulares',
            'anemia',
            'enfermedades_metabolicas',
            'enfermedades_respiratorias',
            'tuberculosis',
            'ets',
            'enfermedades_digestivas',
            'otras',
            'enfermedades_urinarias',
            'enfermedades_oseas',
        ];
    }
}
