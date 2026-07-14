<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cita extends Model
{
    //
    protected $table = 'citas';
    protected $primaryKey = 'idc';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idc', 'idp', 'fec_i', 'fec_f', 'd_user', 'est'
    ];

    protected $casts = [
        'fec_i' => 'datetime',
        'fec_f' => 'datetime',
    ];

    // Relación: Una cita pertenece a un paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idp', 'idp');
    }

    // Relación: Una cita pertenece a un doctor (Usuario)
    public function doctor()
    {
        return $this->belongsTo(Usuario::class, 'd_user', 'user');
    }

    // Genera un UUID para el campo 'idc' al crear una nueva cita
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($cita) {
            if (empty($cita->idc)) {
                $cita->idc = (string) Str::uuid();
            }
        });
    }
    
}
