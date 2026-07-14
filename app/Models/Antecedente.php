<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedente extends Model
{
    //
    protected $table = 'antecedentes';
    protected $primaryKey = 'ide'; // <-- Le avisa a Laravel tu clave primaria personalizada

    protected $fillable = [
        'idp', 'fec', 'hfam', 'ale', 'meda', 'nmed', 'mtel', 'c_tab', 'c_alc', 'c_dro', 'fre', 'san',
        'emb', 'tie', 'lat', 'beb', 'dep', 'ali', 'hig', 'cir', 'cir_des', 'mot', 'inte', 'lab', 'pes',
        'est', 'tem', 'car', 'res', 'pre','ult_rev', 'mot_vis', 'aux_lim', 'aux_cua', 'cep_fre', 'ane_loc',
        'ane_com', 'ane_des', 'rem_cas', 'rem_des', 'dol_mas', 'dol_des', 'san_inf', 'san_des', 'ulc_buc',
        'ulc_fre', 'hab_boc', 'hab_cua', 'obs_buc', 'atm_mov', 'atm_lat', 'atm_cha', 'atm_des', 'ocl_mo',
        'ocl_ca', 'ocl_ovj', 'ocl_ovb', 'tej_b'
    ];

    // Relación inversa: Un antecedente pertenece a un paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idp', 'idp');
    }
}
