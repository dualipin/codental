<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCaja;
use App\Models\Paciente;
use App\Models\PresupuestoDetalle;
use App\Enums\UserRolEnum;
use Illuminate\Http\Request;
use App\Services\CajaService;
use Inertia\Inertia;

class FacturacionController extends Controller
{
    public function index(Request $request)
    {
        $rol = session('rol') ?? (auth()->user() ? auth()->user()->rol : null);
        $puedeRegistrar = in_array($rol, [UserRolEnum::RECEPCIONISTA->value, UserRolEnum::ADMINISTRADOR->value], true);
        $busqueda = trim((string) $request->query('q', ''));

        $pacientes = collect();

        if ($busqueda !== '') {
            $pacientes = Paciente::query()
                ->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'like', '%' . $busqueda . '%')
                        ->orWhere('apellido_paterno', 'like', '%' . $busqueda . '%')
                        ->orWhere('apellido_materno', 'like', '%' . $busqueda . '%')
                        ->orWhere('telefono', 'like', '%' . $busqueda . '%');
                })
                ->orderBy('nombre')
                ->orderBy('apellido_paterno')
                ->limit(20)
                ->get();
        }
        
        $idPac = $request->query('id_pac');

        if ($idPac) {
            session(['paciente_actual' => $idPac]);
        } else {
            $idPac = session('paciente_actual');
        }

        if (! $idPac) {
            return Inertia::render('Facturacion/Index', [
                'rol' => $rol,
                'puedeRegistrar' => $puedeRegistrar,
                'pacientes' => $pacientes,
                'busqueda' => $busqueda,
                'paciente' => null,
                'saldo' => 0,
                'total_cargos' => 0,
                'total_abonos' => 0,
                'tratamientos' => collect(),
                'movimientos' => collect(),
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

        // Movimientos (Ledger inmutable)
        $movimientos = MovimientoCaja::with(['usuario', 'distribuciones.presupuestoDetalle.tratamientoCatalogo'])
            ->where('paciente_id', $idPac)
            ->orderByDesc('fecha_hora')
            ->get();

        $total_abonos = $movimientos->sum('monto');

        return Inertia::render('Facturacion/Index', [
            'rol' => $rol,
            'puedeRegistrar' => $puedeRegistrar,
            'pacientes' => $pacientes,
            'busqueda' => $busqueda,
            'paciente' => $paciente,
            'saldo' => $saldo,
            'total_cargos' => $total_cargos,
            'total_abonos' => $total_abonos,
            'tratamientos' => $tratamientos,
            'movimientos' => $movimientos,
            'idPac' => $idPac,
            'activeTab' => 'facturacion',
        ]);
    }

    public function registrarAbono(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,mixto',
            'referencia_bancaria' => 'nullable|string|max:255',
            'distribucion' => 'required|array|min:1',
            'distribucion.*.presupuesto_detalle_id' => 'required|exists:presupuesto_detalles,id',
            'distribucion.*.monto_aplicado' => 'required|numeric|min:0.01',
        ]);

        $movimiento = app(CajaService::class)->registrarPago(
            (int) $validated['paciente_id'],
            (int) $request->user()->id,
            (float) $validated['monto'],
            (string) $validated['metodo_pago'],
            $validated['referencia_bancaria'] ?? null,
            $validated['distribucion']
        );

        return back()->with('success', 'Pago registrado correctamente.')->with('movimiento_id', $movimiento->id);
    }

    public function anularAbono(Request $request, int $movimiento)
    {
        $this->authorizeVoidPayment($request);

        app(CajaService::class)->anularPago($movimiento, (int) $request->user()->id);

        return back()->with('success', 'Pago anulado correctamente.');
    }

    public function estadoCuenta(Request $request, int $pacienteId)
    {
        $saldo = app(CajaService::class)->calcularSaldoPaciente($pacienteId);

        return response()->json([
            'paciente_id' => $pacienteId,
            'saldo_actual' => $saldo,
        ]);
    }

    public function buscarPacientes(Request $request)
    {
        $term = trim((string) $request->query('q', ''));

        if ($term === '') {
            return response()->json(['data' => []]);
        }

        $pacientes = Paciente::query()
            ->where(function ($query) use ($term) {
                $query->where('nombre', 'like', '%' . $term . '%')
                    ->orWhere('apellido_paterno', 'like', '%' . $term . '%')
                    ->orWhere('apellido_materno', 'like', '%' . $term . '%')
                    ->orWhere('telefono', 'like', '%' . $term . '%');
            })
            ->orderBy('nombre')
            ->orderBy('apellido_paterno')
            ->limit(8)
            ->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'telefono']);

        return response()->json([
            'data' => $pacientes,
        ]);
    }

    private function authorizeVoidPayment(Request $request): void
    {
        if ($request->user()->rol !== UserRolEnum::ADMINISTRADOR->value) {
            abort(403, 'Solo un Administrador puede anular pagos (can_void_payments).');
        }
    }
}
