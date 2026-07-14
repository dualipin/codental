<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Usuario;
use App\Models\Paciente;
use App\UserRolEnum;
use Carbon\Carbon;
use Illuminate\Support\Str;


class AgendaPersonalController extends Controller
{
    public function indexPanel(Request $request)
    {
        $rolUsuario = session('rol');
        $nombreProf = session('nom');
        $usuarioSesion = session('usuario_model') ?: session('id_usuario') ?: session('usuario');

        $dentistas = collect();
        if (in_array($rolUsuario, [UserRolEnum::ADMINISTRADOR->value, UserRolEnum::RECEPCIONISTA->value], true)) {
            $dentistas = Usuario::whereIn('rol', [UserRolEnum::DENTISTA->value, UserRolEnum::ADMINISTRADOR->value])
                ->orderBy('nom')
                ->orderBy('app')
                ->get();
        }

        $dentistaSeleccionado = $request->input('dentista');
        if ($rolUsuario === UserRolEnum::DENTISTA->value) {
            $dentistaSeleccionado = $usuarioSesion;
        } elseif (! $dentistaSeleccionado) {
            $dentistaSeleccionado = $dentistas->first()?->user ?? $usuarioSesion;
        }

        $doctorSeleccionado = $dentistaSeleccionado
            ? Usuario::where('user', $dentistaSeleccionado)->first()
            : null;

        $hoy = Carbon::today();
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();

        $baseCitas = Cita::with(['paciente', 'doctor']);
        if ($dentistaSeleccionado) {
            $baseCitas->where('d_user', $dentistaSeleccionado);
        }

        $citasHoy = (clone $baseCitas)
            ->whereDate('fec_i', $hoy)
            ->orderBy('fec_i')
            ->get();

        $citasSemanales = (clone $baseCitas)
            ->whereBetween('fec_i', [$inicioSemana, $finSemana])
            ->orderBy('fec_i')
            ->get();

        $eventosJson = (clone $baseCitas)
            ->whereBetween('fec_i', [Carbon::now()->subMonth(), Carbon::now()->addMonths(3)])
            ->orderBy('fec_i')
            ->get()
            ->map(function (Cita $cita) {
                $estado = strtolower(trim((string) $cita->est));
                $color = in_array($estado, ['confirmada', 'confirmado', 'aceptada', 'aceptado'], true)
                    ? '#16a34a'
                    : '#dc2626';

                $pacienteNombre = trim(($cita->paciente?->pnom ?? '') . ' ' . ($cita->paciente?->papp ?? '') . ' ' . ($cita->paciente?->papm ?? ''));

                return [
                    'id' => $cita->idc,
                    'title' => $pacienteNombre !== '' ? $pacienteNombre : 'Cita',
                    'start' => Carbon::parse($cita->fec_i)->toIso8601String(),
                    'end' => Carbon::parse($cita->fec_f)->toIso8601String(),
                    'color' => $color,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'estado' => $cita->est,
                        'doctor' => $cita->doctor?->nom,
                        'paciente' => $pacienteNombre,
                    ],
                ];
            });

        $pacientes = collect();
        if (in_array($rolUsuario, [UserRolEnum::ADMINISTRADOR->value, UserRolEnum::RECEPCIONISTA->value], true)) {
            $pacientes = Paciente::orderBy('pnom')->orderBy('papp')->get();
        } elseif ($usuarioSesion) {
            $pacientes = Paciente::where('d_user', $usuarioSesion)->orderBy('pnom')->orderBy('papp')->get();
        }

        $pacienteSeleccionado = $request->input('paciente');

        return view('agenda.index', [
            'rolUsuario' => $rolUsuario,
            'nombreProf' => $nombreProf,
            'dentistas' => $dentistas,
            'dentistaSeleccionado' => $dentistaSeleccionado,
            'doctorSeleccionado' => $doctorSeleccionado,
            'citasHoy' => $citasHoy,
            'citasSemanales' => $citasSemanales,
            'eventosJson' => $eventosJson->toJson(),
            'pacientes' => $pacientes,
            'pacienteSeleccionado' => $pacienteSeleccionado,
        ]);
    }

    public function storeCitaInterna(Request $request)
    {
        $this->authorizeAgendaInterna();

        $request->validate([
            'id_pac' => ['required', 'exists:pacientes,idp'],
            'd_user' => ['required', 'exists:usuarios,user'],
            'fec' => ['required', 'date'],
            'hor' => ['required', 'date_format:H:i'],
            'dur' => ['required', 'integer', 'in:15,30,45,60,90,120,180'],
            'est' => ['nullable', 'in:pendiente,confirmada,confirmado'],
        ]);

        $inicio = Carbon::parse($request->input('fec') . ' ' . $request->input('hor'));
        $fin = $inicio->copy()->addMinutes((int) $request->input('dur'));

        $cruce = Cita::where('d_user', $request->input('d_user'))
            ->where(function ($query) use ($inicio, $fin) {
                $query->where('fec_i', '<', $fin)
                    ->where('fec_f', '>', $inicio);
            })
            ->exists();

        if ($cruce) {
            return back()->withErrors(['hor' => 'Ese horario ya está ocupado para el dentista seleccionado.']);
        }

        Cita::create([
            'idc' => (string) Str::uuid(),
            'idp' => $request->input('id_pac'),
            'fec_i' => $inicio,
            'fec_f' => $fin,
            'd_user' => $request->input('d_user'),
            'est' => $request->input('est', 'pendiente'),
        ]);

        return back()->with('success', 'Cita registrada correctamente.');
    }

    private function authorizeAgendaInterna(): void
    {
        if (! in_array(session('rol'), [UserRolEnum::ADMINISTRADOR->value, UserRolEnum::RECEPCIONISTA->value], true)) {
            abort(403, 'No autorizado.');
        }
    }
}
