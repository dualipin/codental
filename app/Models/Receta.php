<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';
    protected $guarded = [];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function detalles()
    {
        return $this->hasMany(RecetaDetalle::class);
    }
}
