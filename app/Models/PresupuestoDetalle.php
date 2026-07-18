<?php

namespace App\Models;

use Database\Factories\PresupuestoDetalleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresupuestoDetalle extends Model
{
    /** @use HasFactory<PresupuestoDetalleFactory> */
    use HasFactory;

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

    public function distribuciones(): HasMany
    {
        return $this->hasMany(AbonoDistribucion::class, 'presupuesto_detalle_id');
    }
}
