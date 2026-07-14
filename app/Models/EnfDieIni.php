<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnfDieIni extends Model
{
    //
    protected $table = 'enf_die_ini';
    protected $primaryKey = 'id_edi';
    protected $fillable = ['id_dei', 'id_enf', 'obs'];
    
    // Relaciones
    public function dienteEstadoInicial()
    {
        return $this->belongsTo(DienteEstIni::class, 'id_dei');
    }
    
    public function enfermedad()
    {
        return $this->belongsTo(CatEnfermedad::class, 'id_enf');
    }
    
    public function enfermedadesTratamiento()
    {
        return $this->hasMany(EnfDieTrat::class, 'id_edi');
    }
}
