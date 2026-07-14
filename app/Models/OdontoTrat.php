<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontoTrat extends Model
{
    //
    protected $table = 'odonto_trat';
    protected $primaryKey = 'id_odot';
    protected $fillable = ['id_pac', 'id_odoi', 'fec_ult', 'ver', 'act', 'obs'];
    protected $casts = [
        'act' => 'boolean'
    ];
    
    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }
    
    public function odontogramaInicial()
    {
        return $this->belongsTo(OdontoInicial::class, 'id_odoi');
    }
    
    public function dientes()
    {
        return $this->hasMany(DienteEstTrat::class, 'id_odot');
    }
    
    public function historialCambios()
    {
        return $this->hasMany(HistCambio::class, 'id_odot');
    }
}
