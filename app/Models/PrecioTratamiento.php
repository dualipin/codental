<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioTratamiento extends Model
{
    protected $table = 'precios_tratamientos';

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }
}
