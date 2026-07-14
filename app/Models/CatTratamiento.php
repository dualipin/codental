<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatTratamiento extends Model
{
    //
    protected $table = 'cat_tratamientos';
    protected $primaryKey = 'id_trat';
    protected $fillable = ['nom', 'desc', 'cod'];
    
    // Relaciones
    public function precios()
    {
        return $this->hasMany(PrecioTrat::class, 'id_trat');
    }
    
    public function tratamientosSeleccionados()
    {
        return $this->hasMany(PacTratSel::class, 'id_trat');
    }
}
