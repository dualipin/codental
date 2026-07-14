<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DienteEstTrat extends Model
{
    //
    protected $table = 'dientes_est_trat';
    protected $primaryKey = 'id_det';
    protected $fillable = ['id_odot', 'id_die', 'id_car', 'est', 'fec_ini', 'fec_com'];
    
    // Relaciones
    public function odontogramaTratamiento()
    {
        return $this->belongsTo(OdontoTrat::class, 'id_odot');
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
        return $this->hasMany(EnfDieTrat::class, 'id_det');
    }
    
    public function historialCambios()
    {
        return $this->hasMany(HistCambio::class, 'id_det');
    }
}
