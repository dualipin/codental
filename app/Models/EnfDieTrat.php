<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnfDieTrat extends Model
{
    //
    protected $table = 'enf_die_trat';
    protected $primaryKey = 'id_edt';
    protected $fillable = ['id_det', 'id_enf', 'id_edi', 'fec_dia', 'fec_eli', 'est', 'obs'];
    
    // Relaciones
    public function dienteEstadoTratamiento()
    {
        return $this->belongsTo(DienteEstTrat::class, 'id_det');
    }
    
    public function enfermedad()
    {
        return $this->belongsTo(CatEnfermedad::class, 'id_enf');
    }
    
    public function enfermedadInicial()
    {
        return $this->belongsTo(EnfDieIni::class, 'id_edi');
    }
    
    public function tratamientosSeleccionados()
    {
        return $this->hasMany(PacTratSel::class, 'id_edt');
    }
}
