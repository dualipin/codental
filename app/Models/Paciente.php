<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paciente extends Model
{
    //
    protected $table = 'pacientes';
    protected $primaryKey = 'idp';
    public $incrementing = false; // Como es un string (UUID o hash), desactivamos el autoincremento
    protected $keyType = 'string';

    protected $fillable = [
        'idp', 'pnom', 'papp', 'papm', 'pnac', 'psex', 'pciv', 'ptel', 'pocu', 'pcor', 'pest', 'pmun', 'pdir','prel','penv','pmot', 'd_user', 'preal'
    ];

    // Relación: Un paciente tiene muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'idp', 'idp'); 
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($paciente) {
            if (empty($paciente->idp)) {
                $paciente->idp = (string) Str::uuid();
            }
        });
    }

    // Relación: Un paciente tiene un odontograma
    public function odontogramasIniciales()
    {
        return $this->hasMany(OdontoInicial::class, 'id_pac');
    }
    
    public function odontogramasTratamiento()
    {
        return $this->hasMany(OdontoTrat::class, 'id_pac');
    }
    
    public function tratamientosSeleccionados()
    {
        return $this->hasMany(PacTratSel::class, 'id_pac');
    }
    
    public function abonos()
    {
        return $this->hasMany(Abono::class, 'id_pac');
    }
    
    public function caja()
    {
        return $this->hasOne(CajaPac::class, 'id_pac');
    }

}
