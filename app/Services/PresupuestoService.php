<?php

namespace App\Services;

use App\Models\Presupuesto;
use App\Models\PresupuestoDetalle;
use App\Models\TratamientoCatalogo;
use Illuminate\Support\Facades\DB;

class PresupuestoService
{
    /**
     * Crea un presupuesto a partir de un arreglo de detalles.
     * Con esto congelamos el precio actual del catálogo y aplicamos descuentos si están justificados.
     */
    public function crearPresupuesto(int $pacienteId, int $dentistaId, array $detallesData): Presupuesto
    {
        return DB::transaction(function () use ($pacienteId, $dentistaId, $detallesData) {
            
            $totalMonto = 0;

            // Creamos el registro cabecera (Presupuesto)
            $presupuesto = Presupuesto::create([
                'paciente_id' => $pacienteId,
                'dentista_id' => $dentistaId,
                'fecha_emision' => now()->toDateString(),
                'fecha_vencimiento' => now()->addDays(30)->toDateString(),
                'estado' => 'pendiente',
                'monto' => 0, // Se actualizará al final
            ]);

            foreach ($detallesData as $detalle) {
                $catalogo = TratamientoCatalogo::findOrFail($detalle['tratamiento_catalogo_id']);
                
                $descuento = $detalle['monto_descuento'] ?? 0;
                
                if ($descuento > 0 && empty($detalle['justificacion_descuento'])) {
                    throw new \InvalidArgumentException('Debe proveer una justificación para el descuento.');
                }

                $precioCongelado = $catalogo->precio_base_actual;
                $precioFinal = $precioCongelado - $descuento;
                
                if ($precioFinal < 0) {
                    throw new \InvalidArgumentException('El descuento no puede ser mayor al precio del tratamiento.');
                }

                PresupuestoDetalle::create([
                    'presupuesto_id' => $presupuesto->id,
                    'tratamiento_catalogo_id' => $catalogo->id,
                    'referencia_odontograma_id' => $detalle['referencia_odontograma_id'] ?? null,
                    'precio_congelado' => $precioCongelado,
                    'monto_descuento' => $descuento,
                    'justificacion_descuento' => $descuento > 0 ? $detalle['justificacion_descuento'] : null,
                    'estado_tratamiento' => 'pendiente',
                ]);

                $totalMonto += $precioFinal;
            }

            $presupuesto->update(['monto' => $totalMonto]);

            return $presupuesto;
        });
    }
}
