<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacTratSel extends Model
{
    //
    protected $table = 'pac_trat_sel';
    protected $primaryKey = 'id_pts';
    
    protected $fillable = [
        'id_pac', 'id_edt', 'id_trat', 'id_prec', 
        'fec_sel', 'est', 'fec_apr', 'fec_ini', 'fec_com', 
        'mon',
        // Campos nuevos de descuento
        'des', 'tip_des', 'mon_des', 'mon_fin', 'mot_des',
        'obs'
    ];
    
    protected $casts = [
        'mon' => 'decimal:2',
        'des' => 'decimal:2',
        'mon_des' => 'decimal:2',
        'mon_fin' => 'decimal:2',
        'fec_sel' => 'date',
        'fec_apr' => 'date',
        'fec_ini' => 'date',
        'fec_com' => 'date',
    ];
    
    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }
    
    public function enfermedadDienteTratamiento()
    {
        return $this->belongsTo(EnfDieTrat::class, 'id_edt');
    }
    
    public function tratamiento()
    {
        return $this->belongsTo(CatTratamiento::class, 'id_trat');
    }
    
    public function precio()
    {
        return $this->belongsTo(PrecioTrat::class, 'id_prec');
    }
    
    public function abonos()
    {
        return $this->hasMany(Abono::class, 'id_pts');
    }
    
    // Métodos auxiliares
    public function calcularMontoFinal()
    {
        if ($this->tip_des == 'Porcentaje') {
            $this->mon_des = ($this->mon * $this->des) / 100;
        } else {
            $this->mon_des = $this->des;
        }
        $this->mon_fin = $this->mon - $this->mon_des;
        return $this->mon_fin;
    }
    
    public function getMontoConDescuentoAttribute()
    {
        return $this->mon_fin;
    }
    
    public function getDescuentoAplicadoAttribute()
    {
        return $this->mon_des;
    }
}
