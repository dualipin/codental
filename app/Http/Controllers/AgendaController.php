<?php

namespace App\Http\Controllers;

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
                    'title' => $nombreDentista !== '' ? "Cita - Dr(a). {$nombreDentista}" : 'Cita agendada',
                    'start' => $cita->fecha_inicio->toIso8601String(),
                    'end' => $cita->fecha_fin->toIso8601String(),
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#16a34a',
                    'display' => 'block',
                    'extendedProps' => [
                        'dentista_id' => $cita->dentista_id,
                        'dentista_nombre' => $nombreDentista,
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
}
