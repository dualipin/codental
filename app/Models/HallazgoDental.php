<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HallazgoDental extends Model
{
    /** @uses HasFactory<HallazgoDentalFactory> */
    use HasFactory;

    protected $table = 'hallazgos_dentales';
    protected $guarded = [];

    protected $casts = [
        'en_plan' => 'boolean',
    ];

    public function odontograma(): BelongsTo
    {
        return $this->belongsTo(Odontograma::class, 'odontograma_id');
    }

    public function diente(): BelongsTo
    {
        return $this->belongsTo(Diente::class, 'diente_id');
    }

    public function caraDental(): BelongsTo
    {
        return $this->belongsTo(CarasDentales::class, 'cara_dental_id');
    }

    public function enfermedad(): BelongsTo
    {
        return $this->belongsTo(Enfermedad::class, 'enfermedad_id');
    }
}
