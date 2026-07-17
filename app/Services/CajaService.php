<?php

namespace App\Services;

use App\Models\MovimientoCaja;
use App\Models\AbonoDistribucion;
use App\Models\PresupuestoDetalle;
use Illuminate\Support\Facades\DB;

class CajaService
{
    /**
     * Registra un pago (ingreso) de manera inmutable (Append-Only)
     */
    public function registrarPago(int $pacienteId, int $usuarioId, float $monto, string $metodoPago, ?string $referenciaBancaria, array $distribucion): MovimientoCaja
    {
        return DB::transaction(function () use ($pacienteId, $usuarioId, $monto, $metodoPago, $referenciaBancaria, $distribucion) {
            
            // 1. Crear el movimiento (ingreso)
            $movimiento = MovimientoCaja::create([
                'paciente_id' => $pacienteId,
                'tipo_movimiento' => 'ingreso',
                'monto' => abs($monto), // Siempre positivo
                'metodo_pago' => $metodoPago,
                'referencia_bancaria' => $referenciaBancaria,
                'usuario_id' => $usuarioId,
            ]);

            // 2. Distribuir el abono
            $sumaDistribucion = 0;
            foreach ($distribucion as $item) {
                $detalleId = $item['presupuesto_detalle_id'];
                $montoAplicado = abs($item['monto_aplicado']);
                
                AbonoDistribucion::create([
                    'movimiento_caja_id' => $movimiento->id,
                    'presupuesto_detalle_id' => $detalleId,
                    'monto_aplicado' => $montoAplicado,
                ]);

                $sumaDistribucion += $montoAplicado;
            }

            if (round($sumaDistribucion, 2) !== round($monto, 2)) {
                throw new \InvalidArgumentException('La distribución del abono no coincide con el monto total del pago.');
            }

            return $movimiento;
        });
    }

    /**
     * Anula un pago creando un movimiento compensatorio negativo.
     * NUNCA hacemos DELETE o UPDATE del original.
     */
    public function anularPago(int $movimientoOriginalId, int $usuarioId): MovimientoCaja
    {
        return DB::transaction(function () use ($movimientoOriginalId, $usuarioId) {
            $original = MovimientoCaja::findOrFail($movimientoOriginalId);

            if ($original->tipo_movimiento === 'anulacion') {
                throw new \InvalidArgumentException('No se puede anular una anulación.');
            }

            // Crear el registro de anulación
            $anulacion = MovimientoCaja::create([
                'paciente_id' => $original->paciente_id,
                'tipo_movimiento' => 'anulacion',
                'monto' => -$original->monto, // Negativo para compensar
                'metodo_pago' => $original->metodo_pago,
                'referencia_bancaria' => 'Anula #' . $original->id,
                'usuario_id' => $usuarioId,
            ]);

            // Compensar la distribución
            foreach ($original->distribuciones as $dist) {
                AbonoDistribucion::create([
                    'movimiento_caja_id' => $anulacion->id,
                    'presupuesto_detalle_id' => $dist->presupuesto_detalle_id,
                    'monto_aplicado' => -$dist->monto_aplicado,
                ]);
            }

            return $anulacion;
        });
    }

    /**
     * Calcula el estado de cuenta (Saldo Actual) de un paciente.
     * Saldo = Suma(Presupuestos Aprobados) - Suma(Movimientos de Caja)
     */
    public function calcularSaldoPaciente(int $pacienteId): float
    {
        $cargos = DB::table('presupuesto_detalles')
            ->join('presupuestos', 'presupuesto_detalles.presupuesto_id', '=', 'presupuestos.id')
            ->where('presupuestos.paciente_id', $pacienteId)
            ->where('presupuestos.estado', 'aprobado')
            ->selectRaw('SUM(precio_congelado - monto_descuento) as total_cargos')
            ->value('total_cargos') ?? 0;

        $abonos = MovimientoCaja::where('paciente_id', $pacienteId)
            ->sum('monto') ?? 0; // Se suman (los ingresos son + y las anulaciones son -)

        return (float) ($cargos - $abonos);
    }
}
