<?php

namespace App\Models;

use Database\Factories\TratamientoAplicadoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TratamientoAplicado extends Model
{
    /** @use HasFactory<TratamientoAplicadoFactory> */
    use HasFactory;

    protected $table = 'tratamientos_aplicados';
    protected $guarded = [];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function hallazgoDental(): BelongsTo
    {
        return $this->belongsTo(HallazgoDental::class, 'hallazgo_dental_id');
    }

    public function tratamiento(): BelongsTo
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }

    public function precioTratamiento(): BelongsTo
    {
        return $this->belongsTo(PrecioTratamiento::class, 'precio_tratamiento_id');
    }

    public function diente(): BelongsTo
    {
        return $this->belongsTo(Diente::class, 'diente_id');
    }

    public function caraDental(): BelongsTo
    {
        return $this->belongsTo(CarasDentales::class, 'cara_dental_id');
    }

    public function odontologo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }

    protected function casts(): array
    {
        return [
            'fecha_planificada' => 'date',
            'fecha_realizado' => 'date',
        ];
    }
}
