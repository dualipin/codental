<?php

namespace App\Models;

use Database\Factories\PresupuestoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Presupuesto extends Model
{
    /** @use HasFactory<PresupuestoFactory> */
    use HasFactory;

    protected $table = 'presupuestos';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
        ];
    }

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
