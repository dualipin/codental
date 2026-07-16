<?php

namespace App\Http\Controllers;

use App\Enums\EstatusCitaEnum;
use App\Enums\UserRolEnum;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\User;
use Carbon\Carbon;
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
        return view('agenda.index');
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

    public function identificarPacienteShow()
    {
        return view('agenda.identificar-paciente');
    }

    public function identificarPaciente(Request $request)
    {
        $validated = $request->validate([
            'telefono' => 'required|string|max:10',
        ]);

        $pacienteId = Paciente::where('telefono', $validated['telefono'])->first()->id;

        if (!$pacienteId) {
            return back()->withErrors([
                'paciente_no_encontrado' => 'No se encontró un paciente con ese número de teléfono.'
            ]);
        }

        session(['paciente_id' => $pacienteId]);

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

        Cita::create([
            'paciente_id' => $validated['paciente_id'],
            'dentista_id' => $validated['dentista_id'],
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
            'estatus' => EstatusCitaEnum::PENDIENTE->value,
        ]);

        return Inertia::location(route('index'));
    }
}
