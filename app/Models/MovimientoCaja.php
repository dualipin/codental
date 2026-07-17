<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MovimientoCaja extends Model
{
    protected $fillable = [
        'paciente_id',
        'tipo_movimiento',
        'monto',
        'metodo_pago',
        'referencia_bancaria',
        'usuario_id',
        'fecha_hora',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function distribuciones(): HasMany
    {
        return $this->hasMany(AbonoDistribucion::class);
    }
}
