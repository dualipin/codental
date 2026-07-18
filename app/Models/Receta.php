<?php

namespace App\Models;

use Database\Factories\RecetaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    /** @use HasFactory<RecetaFactory> */
    use HasFactory;

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
