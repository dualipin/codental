<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class HistCambio extends Model
{
    //
    protected $table = 'hist_cambios';
    protected $primaryKey = 'id_hist';
    
    protected $fillable = [
        'id_odot', 'id_det', 
        'acc', 'fec_cam', 'usu', 
        'dat_ant', 'dat_nue'
    ];
    
    protected $casts = [
        'dat_ant' => 'array',
        'dat_nue' => 'array',
        'fec_cam' => 'datetime',
    ];
    
    // Relaciones
    public function odontogramaTratamiento()
    {
        return $this->belongsTo(OdontoTrat::class, 'id_odot');
    }
    
    public function dienteEstadoTratamiento()
    {
        return $this->belongsTo(DienteEstTrat::class, 'id_det');
    }
    
    // Métodos auxiliares
    public static function registrarCambio($idOdot, $idDet, $accion, $usuario, $datosAnteriores = null, $datosNuevos = null)
    {
        return self::create([
            'id_odot' => $idOdot,
            'id_det' => $idDet,
            'acc' => $accion,
            'usu' => $usuario,
            'dat_ant' => $datosAnteriores ? json_encode($datosAnteriores) : null,
            'dat_nue' => $datosNuevos ? json_encode($datosNuevos) : null,
        ]);
    }
}
