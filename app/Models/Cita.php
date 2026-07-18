<?php

namespace App\Models;

use App\Enums\EstatusCitaEnum;
use Database\Factories\CitaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    /** @use HasFactory<CitaFactory> */
    use HasFactory;

    protected $table = 'citas';
    protected $guarded = [];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function dentista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentista_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por_id');
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function evolucionClinica()
    {
        return $this->hasOne(EvolucionClinica::class, 'cita_id');
    }

    public function receta()
    {
        return $this->hasOne(Receta::class, 'cita_id');
    }

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'estatus' => EstatusCitaEnum::class,
        ];
    }
}
