<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odontograma extends Model
{
    protected $guarded = [];

    protected $casts = [
        'estado_dientes' => 'array', // Guardará el mapeo de cada diente y su estado
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}