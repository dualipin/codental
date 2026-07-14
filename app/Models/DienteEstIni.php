<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DienteEstIni extends Model
{
    //
    protected $table = 'dientes_est_ini';
    protected $primaryKey = 'id_dei';
    protected $fillable = ['id_odoi', 'id_die', 'id_car'];
    
    // Relaciones
    public function odontogramaInicial()
    {
        return $this->belongsTo(OdontoInicial::class, 'id_odoi');
    }
    
    public function diente()
    {
        return $this->belongsTo(CatDiente::class, 'id_die');
    }
    
    public function cara()
    {
        return $this->belongsTo(CatCara::class, 'id_car');
    }
    
    public function enfermedades()
    {
        return $this->hasMany(EnfDieIni::class, 'id_dei');
    }
}
