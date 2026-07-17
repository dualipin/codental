<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCaja;
use App\Models\Paciente;
use App\Models\PresupuestoDetalle;
use App\Enums\UserRolEnum;
use Illuminate\Http\Request;
use App\Services\CajaService;

class FacturacionController extends Controller
{
    public function index(Request $request)
    {
        $rol = session('rol') ?? (auth()->user() ? auth()->user()->rol : null);
        $puedeRegistrar = in_array($rol, [UserRolEnum::RECEPCIONISTA->value, UserRolEnum::ADMINISTRADOR->value], true);

        // Los pacientes son globales ahora (sin d_user)
        $pacientes = Paciente::query()->orderBy('nombre')->orderBy('apellido_paterno')->get();
        
        $idPac = $request->query('id_pac');

        if ($idPac) {
            session(['paciente_actual' => $idPac]);
        } else {
            $idPac = session('paciente_actual');
        }

        if (! $idPac && $pacientes->isNotEmpty()) {
            $idPac = $pacientes->first()->id;
        }

        if (! $idPac) {
            return view('facturacion', [
                'rol' => $rol,
                'puedeRegistrar' => $puedeRegistrar,
                'pacientes' => $pacientes,
                'paciente' => null,
                'saldo' => 0,
                'total_cargos' => 0,
                'total_abonos' => 0,
                'tratamientos' => collect(),
                'abonos' => collect(),
                'activeTab' => 'facturacion',
            ]);
        }

        $paciente = Paciente::findOrFail($idPac);
        
        $cajaService = app(CajaService::class);
        $saldo = $cajaService->calcularSaldoPaciente($idPac);

        // Tratamientos Aprobados (Cargos)
        $tratamientos = PresupuestoDetalle::with(['tratamientoCatalogo', 'presupuesto'])
            ->whereHas('presupuesto', function($q) use ($idPac) {
                $q->where('paciente_id', $idPac)->where('estado', 'aprobado');
            })
            ->get();

        $total_cargos = $tratamientos->sum(function($t) {
            return $t->precio_congelado - $t->monto_descuento;
        });

        // Movimientos (Abonos y Anulaciones)
        $abonos = MovimientoCaja::with('usuario')
            ->where('paciente_id', $idPac)
            ->orderByDesc('fecha_hora')
            ->get();

        $total_abonos = $abonos->sum('monto');

        return view('facturacion', [
            'rol' => $rol,
            'puedeRegistrar' => $puedeRegistrar,
            'pacientes' => $pacientes,
            'paciente' => $paciente,
            'saldo' => $saldo,
            'total_cargos' => $total_cargos,
            'total_abonos' => $total_abonos,
            'tratamientos' => $tratamientos,
            'abonos' => $abonos,
            'idPac' => $idPac,
            'activeTab' => 'facturacion',
        ]);
    }
}
