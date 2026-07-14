<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaPac extends Model
{
    //
    protected $table = 'caja_pac';
    protected $primaryKey = 'id_caja';
    
    protected $fillable = [
        'id_pac', 
        'mon_tot', 'mon_abo', 'sal_pen', 
        'fec_ult_abo', 'est_cue',
        // Campos nuevos
        'des_tot', 'mon_net'
    ];
    
    protected $casts = [
        'mon_tot' => 'decimal:2',
        'mon_abo' => 'decimal:2',
        'sal_pen' => 'decimal:2',
        'des_tot' => 'decimal:2',
        'mon_net' => 'decimal:2',
        'fec_ult_abo' => 'date',
    ];
    
    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }
    
    // Métodos auxiliares
    public function actualizarSaldo()
    {
        $totalTratamientos = PacTratSel::where('id_pac', $this->id_pac)
            ->whereNotIn('est', ['Cancelado'])
            ->sum('mon_fin');
        
        $totalAbonos = Abono::where('id_pac', $this->id_pac)
            ->where('est', 'Activo')
            ->sum('mon');
        
        $this->mon_tot = $totalTratamientos;
        $this->mon_abo = $totalAbonos;
        $this->sal_pen = $totalTratamientos - $totalAbonos;
        $this->est_cue = $this->sal_pen > 0 ? 'Adeudo' : 'Pagado en total';
        $this->save();
        
        return $this;
    }
}
