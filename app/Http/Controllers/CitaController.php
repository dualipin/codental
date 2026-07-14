<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\UserRolEnum;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Paciente;
use Inertia\Inertia;


class CitaController extends Controller
{

    public function index()
    {
        return Inertia::render('AgendarCitaPage');
    }

    // Carga la primera vista (Modal / Selección)
    public function pantallaIdentificacion()
    {
        $doctores = User::all()->where('rol', UserRolEnum::DENTISTA->value);

        // Pasamos la variable a la vista de identificación
        return view('agenda.identificacion', compact('doctores'));
    }

    // Verifica si el paciente ya existe por Nombre, Apellidos y Teléfono
    public function verificarPaciente(Request $request)
    {
        $request->validate([
            'pnom' => 'required|string',
            'papp' => 'required|string',
            'papm' => 'required|string',
            'ptel' => 'required|string|max:10',
        ]);

        // Buscamos coincidencia exacta en la base de datos
        $paciente = Paciente::where('pnom', $request->pnom)
            ->where('papp', $request->papp)
            ->where('papm', $request->papm)
            ->where('ptel', $request->ptel)
            ->first();

        if (!$paciente) {
            return back()->withErrors(
                ['error_paciente' => 'No se encontró ningún registro con los datos proporcionados.']
            );
        }

        // Si existe, guardamos su ID en la sesión segura del servidor
        session(['agenda_id_paciente' => $paciente->idp]);

        return redirect()->route('agenda.calendario');
    }

    // Carga la segunda vista (El Calendario con los datos automáticos)
    public function pantallaAgenda()
    {
        // Seguridad: Si no hay ID en la sesión, lo regresa al paso 1
        if (!session()->has('agenda_id_paciente')) {
            return redirect()->route('agenda.identificacion');
        }

        // Recuperamos el ID de la sesión y buscamos al paciente con su doctor asignado
        $idPaciente = session('agenda_id_paciente');
        $paciente = Paciente::findOrFail($idPaciente);

        // Obtenemos el doctor asignado directamente de la ficha del paciente
        $doctorAsignado = Usuario::where('user', $paciente->d_user)->first();

        // Cargar todos los eventos del doctor seleccionado para FullCalendar
        $citasRaw = Cita::with(['paciente'])
            ->where('d_user', $doctorAsignado?->user ?? $paciente->d_user)
            ->orderBy('fec_i')
            ->get();

        $eventos = $citasRaw->map(function ($cita) {
            $estado = strtolower(trim((string)$cita->est));

            if (in_array($estado, ['aceptada', 'aceptado', 'confirmada', 'confirmado'], true)) {
                $color = '#16a34a';
                $estadoTexto = 'Aceptada';
            } elseif (in_array($estado, ['pendiente', 'pendiente de confirmar'], true)) {
                $color = '#dc2626';
                $estadoTexto = 'Pendiente';
            } else {
                $color = '#64748b';
                $estadoTexto = ucfirst($estado ?: 'Sin estado');
            }

            $pacienteNombre = trim(
                ($cita->paciente->pnom ?? '') . ' ' . ($cita->paciente->papp ?? '') . ' ' . ($cita->paciente->papm ?? '')
            );

            return [
                'title' => $pacienteNombre !== '' ? $pacienteNombre : 'Cita agendada',
                'start' => Carbon::parse($cita->fec_i)->toIso8601String(),
                'end' => Carbon::parse($cita->fec_f)->toIso8601String(),
                'allDay' => false,
                'color' => $color,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'estado' => $estado,
                    'estadoTexto' => $estadoTexto,
                    'doctorName' => 'Dr(a). ' . trim(
                            ($doctorAsignado?->nom ?? '') . ' ' . ($doctorAsignado?->app ?? '')
                        ),
                    'paciente' => $pacienteNombre,
                    'doctorId' => $cita->d_user,
                ],
            ];
        });

        return view('agenda.calendario', [
            'paciente' => $paciente,
            'doctor' => $doctorAsignado,
            'eventosJson' => $eventos->toJson(),
        ]);
    }

    // Procesa el guardado final de la cita
    public function storeDesdePaciente(Request $request)
    {
        if (!session()->has('agenda_id_paciente')) {
            return redirect()->route('agenda.identificacion');
        }

        $idPaciente = session('agenda_id_paciente');
        $paciente = Paciente::findOrFail($idPaciente);

        $request->validate([
            'fec' => 'required|date',
            'hor' => 'required'
        ]);

        $inicio = Carbon::parse($request->fec . ' ' . $request->hor);
        $fin = $inicio->copy()->addHour();

        // Verificar cruces de última hora
        $yaExiste = Cita::where('d_user', $paciente->d_user)
            ->where(function ($query) use ($inicio, $fin) {
                $query->where('fec_i', '<', $fin)->where('fec_f', '>', $inicio);
            })->exists();

        if ($yaExiste) {
            return back()->withErrors(['hor' => 'Este horario se acaba de ocupar. Elige otro.']);
        }

        Cita::create([
            'idc' => (string)Str::uuid(),
            'idp' => $paciente->idp, // ID seguro desde la base de datos
            'fec_i' => $inicio,
            'fec_f' => $fin,
            'd_user' => $paciente->d_user, // Doctor asignado automáticamente
            'est' => 'pendiente'
        ]);

        // Limpiamos la sesión para que el proceso termine correctamente
        session()->forget('agenda_id_paciente');

        return redirect()->route('agenda.identificacion')->with('success', '¡Cita agendada con éxito!');
    }


}
