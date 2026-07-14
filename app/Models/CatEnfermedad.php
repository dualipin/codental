<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatEnfermedad extends Model
{
    //
    protected $table = 'cat_enfermedades';
    protected $primaryKey = 'id_enf';
    
    protected $fillable = [
        'nom', 'desc', 'cod', 'grav',
        'color'  // NUEVO
    ];
    
    // Relaciones
    public function enfermedadesIniciales()
    {
        return $this->hasMany(EnfDieIni::class, 'id_enf');
    }
    
    public function enfermedadesTratamiento()
    {
        return $this->hasMany(EnfDieTrat::class, 'id_enf');
    }
}
