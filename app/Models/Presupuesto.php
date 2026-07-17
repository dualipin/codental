<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';
    protected $guarded = [];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function dentista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentista_id');
    }

    public function abonos(): HasMany
    {
        return $this->hasMany(Abono::class, 'presupuesto_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(PresupuestoDetalle::class, 'presupuesto_id');
    }

    public function distribuciones(): HasManyThrough
    {
        return $this->hasManyThrough(
            AbonoDistribucion::class,
            PresupuestoDetalle::class,
            'presupuesto_id',
            'presupuesto_detalle_id'
        );
    }
}
