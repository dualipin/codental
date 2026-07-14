<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatCara extends Model
{
    //
    protected $table = 'cat_caras';
    protected $primaryKey = 'id_cara';
    protected $fillable = ['nom', 'abr', 'desc'];
    
    // Relaciones
    public function dientesIniciales()
    {
        return $this->hasMany(DienteEstIni::class, 'id_car');
    }
    
    public function dientesTratamiento()
    {
        return $this->hasMany(DienteEstTrat::class, 'id_car');
    }
}
