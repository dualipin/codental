<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoDetalle extends Model
{
    protected $fillable = [
        'presupuesto_id',
        'tratamiento_catalogo_id',
        'referencia_odontograma_id',
        'precio_congelado',
        'monto_descuento',
        'justificacion_descuento',
        'estado_tratamiento',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function tratamientoCatalogo(): BelongsTo
    {
        return $this->belongsTo(TratamientoCatalogo::class);
    }
}
