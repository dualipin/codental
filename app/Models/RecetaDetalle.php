<?php

namespace App\Models;

use Database\Factories\RecetaDetalleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecetaDetalle extends Model
{
    /** @use HasFactory<RecetaDetalleFactory> */
    use HasFactory;

    protected $table = 'receta_detalles';
    protected $guarded = [];

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }
}
