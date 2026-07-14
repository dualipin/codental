<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioTrat extends Model
{
    //
    protected $table = 'precios_trat';
    protected $primaryKey = 'id_precio';
    protected $fillable = ['id_trat', 'tip_die', 'prec', 'prec_ant', 'vig_des', 'vig_has'];
    
    // Relaciones
    public function tratamiento()
    {
        return $this->belongsTo(CatTratamiento::class, 'id_trat');
    }
    
    public function tratamientosSeleccionados()
    {
        return $this->hasMany(PacTratSel::class, 'id_prec');
    }
}
