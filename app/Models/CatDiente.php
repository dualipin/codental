<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatDiente extends Model
{
    //
    protected $table = 'cat_dientes';
    protected $primaryKey = 'id_diente';
    protected $fillable = ['num_fdi', 'nom', 'cuad', 'tipo', 'pos'];
    
    // Relaciones
    public function dientesIniciales()
    {
        return $this->hasMany(DienteEstIni::class, 'id_die');
    }
    
    public function dientesTratamiento()
    {
        return $this->hasMany(DienteEstTrat::class, 'id_die');
    }
}
