<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaDetalle extends Model
{
    protected $table = 'receta_detalles';
    protected $guarded = [];

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }
}
