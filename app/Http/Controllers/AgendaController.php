<?php

namespace App\Http\Controllers;

use App\Enums\EstatusCitaEnum;
use App\Enums\UserRolEnum;
use App\Models\Cita;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Inertia\Inertia;

use function auth;
use function redirect;
use function view;

class AgendaController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();

        if (! $usuario) {
            return redirect()->route('login.show');
        }

        $rolUsuario = $usuario->rol instanceof UserRolEnum
            ? $usuario->rol->value
            : (string) $usuario->rol;

        $citasQuery = Cita::with('dentista')
            ->where('fecha_inicio', '>=', Carbon::now()->startOfWeek(Carbon::MONDAY));

        if ($rolUsuario === UserRolEnum::DENTISTA->value) {
            $citasQuery->where('dentista_id', $usuario->id);
        }

        $citas = $citasQuery
            ->with(['dentista', 'paciente'])
            ->orderBy('fecha_inicio')
            ->get()
            ->map(function (Cita $cita) {
                $nombreDentista = trim(collect([
                    $cita->dentista?->nombre,
                    $cita->dentista?->apellido_paterno,
                    $cita->dentista?->apellido_materno,
                ])->filter()->join(' '));

                return [
                    'id' => $cita->id,
                    'estatus' => $cita->estatus->value,
                    'title' => trim(collect([
                        $cita->paciente?->nombre,
                        $cita->paciente?->apellido_paterno,
                    ])->filter()->join(' ')) ?: 'Cita agendada',
                    'start' => $cita->fecha_inicio->toIso8601String(),
                    'end' => $cita->fecha_fin->toIso8601String(),
                    'backgroundColor' => $cita->estatus === EstatusCitaEnum::PENDIENTE ? '#f59e0b' : '#22c55e',
                    'borderColor' => $cita->estatus === EstatusCitaEnum::PENDIENTE ? '#d97706' : '#16a34a',
                    'display' => 'block',
                    'extendedProps' => [
                        'dentista_id' => $cita->dentista_id,
                        'dentista_nombre' => $nombreDentista,
                        'paciente_id' => $cita->paciente_id,
                        'paciente_nombre' => $cita->paciente?->nombre,
                        'paciente_apellido_paterno' => $cita->paciente?->apellido_paterno,
                        'paciente_apellido_materno' => $cita->paciente?->apellido_materno,
                        'confirmacion_url' => route('agenda.citas.confirmar', ['cita' => $cita->id]),
                    ],
                ];
            });

        $doctores = collect();

        if (in_array($rolUsuario, [UserRolEnum::ADMINISTRADOR->value, UserRolEnum::RECEPCIONISTA->value], true)) {
            $doctores = User::query()
                ->whereIn('rol', [UserRolEnum::DENTISTA->value, UserRolEnum::ADMINISTRADOR->value])
                ->orderBy('nombre')
                ->get([
                    'id',
                    'nombre',
                    'apellido_paterno',
                    'apellido_materno',
                ]);
        }

        return Inertia::render('Agenda/Calendario', [
            'rolUsuario' => $rolUsuario,
            'usuarioId' => $usuario->id,
            'citas' => $citas,
            'doctores' => $doctores,
        ]);
    }

    public function confirmar(Cita $cita)
    {
        $cita->load([
            'paciente.historiaClinica',
            'paciente.citas' => fn ($query) => $query->with('dentista')->latest('fecha_inicio'),
            'paciente.consultas' => fn ($query) => $query->with('odontologo')->latest('fecha_consulta'),
            'paciente.presupuestos' => fn ($query) => $query->with('dentista', 'abonos')->latest('fecha_emision'),
            'dentista',
        ]);

        return Inertia::render('Agenda/ConfirmarCita', [
            'cita' => [
                'id' => $cita->id,
                'fecha_inicio' => $cita->fecha_inicio->toIso8601String(),
                'fecha_fin' => $cita->fecha_fin->toIso8601String(),
                'motivo' => $cita->motivo,
                'estatus' => $cita->estatus->value,
                'paciente' => [
                    'id' => $cita->paciente->id,
                    'nombre' => $cita->paciente->nombre,
                    'apellido_paterno' => $cita->paciente->apellido_paterno,
                    'apellido_materno' => $cita->paciente->apellido_materno,
                    'telefono' => $cita->paciente->telefono,
                    'correo_electronico' => $cita->paciente->correo_electronico,
                    'fecha_nacimiento' => $cita->paciente->fecha_nacimiento?->toDateString(),
                    'sexo' => $cita->paciente->sexo?->value,
                    'direccion' => $cita->paciente->direccion,
                    'estado' => $cita->paciente->estado,
                    'municipio' => $cita->paciente->municipio,
                    'ocupacion' => $cita->paciente->ocupacion,
                    'estado_civil' => $cita->paciente->estado_civil,
                    'religion' => $cita->paciente->religion,
                    'verificado' => (bool) $cita->paciente->verificado,
                    'historiaClinica' => $cita->paciente->historiaClinica ? [
                        'antecedentes_hereditarios' => $cita->paciente->historiaClinica->antecedentes_hereditarios,
                        'alergias' => $cita->paciente->historiaClinica->alergias,
                        'medicacion_actual' => $cita->paciente->historiaClinica->medicacion_actual,
                        'nombre_medico' => $cita->paciente->historiaClinica->nombre_medico,
                        'telefono_medico' => $cita->paciente->historiaClinica->telefono_medico,
                        'enfermedades_previas' => $cita->paciente->historiaClinica->enfermedades_previas,
                        'otras_enfermedades' => $cita->paciente->historiaClinica->otras_enfermedades,
                        'habitos_toxicos' => $cita->paciente->historiaClinica->habitos_toxicos,
                        'grupo_sanguineo' => $cita->paciente->historiaClinica->grupo_sanguineo,
                        'ginecoobstetricos' => $cita->paciente->historiaClinica->ginecoobstetricos,
                        'estilo_vida' => $cita->paciente->historiaClinica->estilo_vida,
                        'cirugias_hospitalizaciones' => $cita->paciente->historiaClinica->cirugias_hospitalizaciones,
                        'padecimiento_actual' => $cita->paciente->historiaClinica->padecimiento_actual,
                        'interrogatorio_sistemas' => $cita->paciente->historiaClinica->interrogatorio_sistemas,
                        'examenes_laboratorio' => $cita->paciente->historiaClinica->examenes_laboratorio,
                        'antecedentes_bucodentales' => $cita->paciente->historiaClinica->antecedentes_bucodentales,
                        'atm' => $cita->paciente->historiaClinica->atm,
                        'tejidos_blandos_duros' => $cita->paciente->historiaClinica->tejidos_blandos_duros,
                    ] : null,
                ],
                'dentista' => [
                    'id' => $cita->dentista->id,
                    'nombre' => $cita->dentista->nombre,
                    'apellido_paterno' => $cita->dentista->apellido_paterno,
                    'apellido_materno' => $cita->dentista->apellido_materno,
                    'especialidad' => $cita->dentista->especialidad,
                ],
            ],
        ]);
    }

    public function confirmarCita(Cita $cita)
    {
        if ($cita->estatus !== EstatusCitaEnum::PENDIENTE) {
            return back()->withErrors(['estatus' => 'Solo se pueden confirmar citas pendientes.']);
        }

        $cita->update(['estatus' => EstatusCitaEnum::CONFIRMADA->value]);

        return redirect()->route('agenda.citas.confirmar', ['cita' => $cita->id]);
    }

    public function cancelarCita(Cita $cita)
    {
        if ($cita->estatus === EstatusCitaEnum::CANCELADA) {
            return back()->withErrors(['estatus' => 'La cita ya está cancelada.']);
        }

        $cita->update(['estatus' => EstatusCitaEnum::CANCELADA->value]);

        return redirect()->route('agenda.citas.confirmar', ['cita' => $cita->id]);
    }
}
