<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbonoDistribucion extends Model
{
    protected $fillable = [
        'movimiento_caja_id',
        'presupuesto_detalle_id',
        'monto_aplicado',
    ];

    public function movimientoCaja(): BelongsTo
    {
        return $this->belongsTo(MovimientoCaja::class);
    }

    public function presupuestoDetalle(): BelongsTo
    {
        return $this->belongsTo(PresupuestoDetalle::class);
    }
}
