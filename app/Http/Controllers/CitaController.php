<?php

namespace App\Http\Controllers;

use App\Enums\EstatusCitaEnum;
use App\Enums\SexoEnum;
use App\Enums\UserRolEnum;
use App\Models\Cita;
use App\Models\HistoriaClinica;
use App\Models\Paciente;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function asset;
use function back;
use function redirect;
use function route;
use function session;


class CitaController extends Controller
{

    public function index()
    {
        return view('agendar-cita.index');
    }

    public function registrarPacienteShow()
    {
        $doctores = User::query()
            ->whereIn('rol', [UserRolEnum::DENTISTA->value, UserRolEnum::ADMINISTRADOR->value])
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
                'apellido_paterno',
                'especialidad',
                'foto_usuario',
            ])
            ->map(function ($doctor) {
                $doctor->foto_usuario = $doctor->foto_usuario
                    ? asset('storage/' . $doctor->foto_usuario)
                    : null;

                return $doctor;
            });

        return Inertia::render('AgendarCita/RegistrarPaciente', [
            'doctores' => $doctores,
            'formulario_publico' => true
        ]);
    }

    public function registrarPaciente(Request $request)
    {
        $validated = $request->validate([
            'formulario_publico' => 'required|boolean',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F,O',
            'ocupacion' => 'nullable|string|max:100',
            'estado_civil' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:10',
            'correo_electronico' => 'nullable|email|max:150',
            'estado' => 'nullable|string|max:100',
            'municipio' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',
            'usuario_asignado' => 'nullable|integer|exists:users,id',
            'antecedentes_hereditarios' => 'nullable|string',
            'alergias' => 'nullable|string',
            'medicacion_actual' => 'nullable|string',
            'nombre_medico' => 'nullable|string|max:150',
            'telefono_medico' => 'nullable|string|max:20',
            'enfermedades_previas' => 'nullable|array',
            'otras_enfermedades' => 'nullable|string',
            'habitos_toxicos' => 'nullable|array',
            'grupo_sanguineo' => 'nullable|string|max:10',
            'ginecoobstetricos' => 'nullable|array',
            'estilo_vida' => 'nullable|array',
            'cirugias_hospitalizaciones' => 'nullable|string',
            'padecimiento_actual' => 'nullable|string',
            'interrogatorio_sistemas' => 'nullable|string',
            'examenes_laboratorio' => 'nullable|string',
            'antecedentes_bucodentales' => 'nullable|array',
            'atm' => 'nullable|array',
            'tejidos_blandos_duros' => 'nullable|string',
        ]);

        $pacienteExistente = Paciente::where('telefono', $validated['telefono'])->first();

        if ($pacienteExistente) {
            session(['paciente_id' => $pacienteExistente->id]);

            return redirect()->route('agendar-cita.calendario.show');
        }

        $paciente = Paciente::create([
            'nombre' => $validated['nombre'],
            'apellido_paterno' => $validated['apellido_paterno'],
            'apellido_materno' => $validated['apellido_materno'] ?? '',
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'sexo' => SexoEnum::tryFrom($validated['sexo']) ?? SexoEnum::OTRO,
            'ocupacion' => $validated['ocupacion'] ?? '',
            'estado_civil' => $validated['estado_civil'] ?? '',
            'telefono' => $validated['telefono'],
            'correo_electronico' => $validated['correo_electronico'] ?? '',
            'estado' => $validated['estado'] ?? '',
            'municipio' => $validated['municipio'] ?? '',
            'direccion' => $validated['direccion'] ?? '',
            'religion' => $validated['religion'] ?? '',
        ]);

        HistoriaClinica::create([
            'paciente_id' => $paciente->id,
            'antecedentes_hereditarios' => $validated['antecedentes_hereditarios'] ?? '',
            'alergias' => $validated['alergias'] ?? '',
            'medicacion_actual' => $validated['medicacion_actual'] ?? '',
            'nombre_medico' => $validated['nombre_medico'] ?? '',
            'telefono_medico' => $validated['telefono_medico'] ?? '',
            'enfermedades_previas' => $validated['enfermedades_previas'] ?? [],
            'otras_enfermedades' => $validated['otras_enfermedades'] ?? '',
            'habitos_toxicos' => $validated['habitos_toxicos'] ?? [],
            'grupo_sanguineo' => $validated['grupo_sanguineo'] ?? '',
            'ginecoobstetricos' => $validated['ginecoobstetricos'] ?? [],
            'estilo_vida' => $validated['estilo_vida'] ?? [],
            'cirugias_hospitalizaciones' => $validated['cirugias_hospitalizaciones'] ?? '',
            'padecimiento_actual' => $validated['padecimiento_actual'] ?? '',
            'interrogatorio_sistemas' => $validated['interrogatorio_sistemas'] ?? '',
            'examenes_laboratorio' => $validated['examenes_laboratorio'] ?? '',
            'antecedentes_bucodentales' => $validated['antecedentes_bucodentales'] ?? [],
            'atm' => $validated['atm'] ?? [],
            'tejidos_blandos_duros' => $validated['tejidos_blandos_duros'] ?? '',
        ]);

        session(['paciente_id' => $paciente->id]);

        return Inertia::location(route('agendar-cita.calendario.show'));
    }

    public function identificarPacienteShow()
    {
        return view('agendar-cita.identificar-paciente');
    }

    public function identificarPaciente(Request $request)
    {
        $validated = $request->validate([
            'telefono' => 'required|string|max:10',
        ]);

        $paciente = Paciente::where('telefono', $validated['telefono'])->first();

        if (! $paciente) {
            return back()->withErrors([
                'paciente_no_encontrado' => 'No se encontró un paciente con ese número de teléfono.'
            ]);
        }

        session(['paciente_id' => $paciente->id]);

        return redirect()->route('agendar-cita.calendario.show');
    }

    public function calendarioShow()
    {
        $pacienteId = session('paciente_id');

        $doctores = User::query()
            ->whereIn('rol', [UserRolEnum::DENTISTA->value, UserRolEnum::ADMINISTRADOR->value])
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'especialidad',
                'foto_usuario',
            ])
            ->map(function ($doctor) {
                $doctor->foto_usuario = $doctor->foto_usuario
                    ? asset('storage/' . $doctor->foto_usuario)
                    : null;

                return $doctor;
            });

        $citas = Cita::with('dentista')
            ->where('fecha_inicio', '>=', Carbon::now()->startOfWeek(Carbon::MONDAY))
            ->get()
            ->map(function (Cita $cita) {
                return [
                    'id' => $cita->id,
                    'title' => 'Ocupado',
                    'start' => $cita->fecha_inicio->toIso8601String(),
                    'end' => $cita->fecha_fin->toIso8601String(),
                    'backgroundColor' => '#f87171',
                    'borderColor' => '#ef4444',
                    'display' => 'block',
                    'extendedProps' => [
                        'dentista_id' => $cita->dentista_id,
                    ],
                ];
            });

        return Inertia::render('AgendarCita/Calendario', [
            'paciente_id' => $pacienteId,
            'doctores' => $doctores,
            'citas' => $citas,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'dentista_id' => ['required', 'exists:users,id'],
            'fecha_inicio' => ['required', 'date', 'after:now'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'motivo' => ['nullable', 'string', 'max:500'],
        ]);

        $inicio = Carbon::parse($validated['fecha_inicio']);
        $fin = Carbon::parse($validated['fecha_fin']);

        $cruce = Cita::where('dentista_id', $validated['dentista_id'])
            ->where(function ($query) use ($inicio, $fin) {
                $query->where('fecha_inicio', '<', $fin)
                    ->where('fecha_fin', '>', $inicio);
            })
            ->exists();

        if ($cruce) {
            return back()->withErrors([
                'horario' => 'Ese horario ya está ocupado para el dentista seleccionado.',
            ]);
        }

        $cita = Cita::create([
            'paciente_id' => $validated['paciente_id'],
            'dentista_id' => $validated['dentista_id'],
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
            'motivo' => $validated['motivo'] ?? null,
            'estatus' => EstatusCitaEnum::PENDIENTE->value,
        ]);

        return Inertia::location(route('agendar-cita.confirmacion', ['cita' => $cita->id]));
    }

    public function confirmacion(Cita $cita)
    {
        $cita->load(['paciente', 'dentista']);

        return Inertia::render('AgendarCita/ConfirmacionCita', [
            'cita' => [
                'id' => $cita->id,
                'fecha_inicio' => $cita->fecha_inicio->format('Y-m-d H:i:s'),
                'fecha_fin' => $cita->fecha_fin->format('Y-m-d H:i:s'),
                'motivo' => $cita->motivo,
                'estatus' => $cita->estatus->value,
                'paciente' => [
                    'id' => $cita->paciente->id,
                    'nombre' => $cita->paciente->nombre,
                    'apellido_paterno' => $cita->paciente->apellido_paterno,
                    'apellido_materno' => $cita->paciente->apellido_materno,
                    'telefono' => $cita->paciente->telefono,
                    'email' => $cita->paciente->email,
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

    public function descargarPdf(Cita $cita)
    {
        $cita->load(['paciente', 'dentista']);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $html = view('pdf.cita-confirmacion', [
            'cita' => $cita,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        return response()->streamDownload(
            fn() => print($dompdf->output()),
            "cita-{$cita->id}-{$cita->paciente->apellido_paterno}.pdf",
        );
    }
}
