<?php

namespace App\Models;

use App\Enums\TipoSeguimientoOdontogramaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Odontograma extends Model
{
    /** @uses HasFactory<OdontogramaFactory> */
    use HasFactory;

    protected $table = 'odontogramas';
    protected $guarded = [];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function odontologo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function hallazgos(): HasMany
    {
        return $this->hasMany(HallazgoDental::class, 'odontograma_id');
    }

    protected function casts(): array
    {
        return [
            'tipo_seguimiento' => TipoSeguimientoOdontogramaEnum::class,
            'fecha' => 'date',
        ];
    }
}