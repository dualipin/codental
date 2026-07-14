<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    //
    protected $table = 'abonos_movs';
    protected $primaryKey = 'id_abo';

    protected $fillable = [
        'id_pac',
        'id_pts',
        'mon',
        'fec_abo',
        'met_pag',
        'ref',
        'obs',
        'des_apl',
        'est',
        'id_usuario_registro',
        'mot_anulacion'
    ];
    
    protected $casts = [
        'mon' => 'decimal:2',
        'des_apl' => 'decimal:2',
        'fec_abo' => 'date',
    ];
    
    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }
    
    public function tratamientoSeleccionado()
    {
        return $this->belongsTo(PacTratSel::class, 'id_pts');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro', 'user');
    }
}
