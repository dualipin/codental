<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontoInicial extends Model
{
    //
    protected $table = 'odonto_inicial';
    protected $primaryKey = 'id_odoi';
    protected $fillable = ['id_pac', 'fec_reg', 'obs'];
    
    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }
    
    public function dientes()
    {
        return $this->hasMany(DienteEstIni::class, 'id_odoi');
    }
    
    public function odontogramasTratamiento()
    {
        return $this->hasMany(OdontoTrat::class, 'id_odoi');
    }
}
