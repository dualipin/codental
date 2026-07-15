<?php

namespace App\Models;

use App\Enums\TipoSeguimientoOdontogramaEnum;
use Illuminate\Database\Eloquent\Model;

class Odontograma extends Model
{
    protected $table = 'odontogramas';
    protected $guarded = [];

    public function odontologo()
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }

    protected function casts(): array
    {
        return [
            'tipo_seguimiento' => TipoSeguimientoOdontogramaEnum::class,
        ];
    }
}