<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Paciente;
use App\Models\Antecedente;
use App\Models\Usuario;
use App\Models\Cita;
use Carbon\Carbon;



class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $rol = session('rol');
        $usuarioSesion = session('usuario_model') ?: session('id_usuario') ?: session('usuario');

        $query = Paciente::query()->with(['citas' => function ($q) {
            $q->orderByDesc('fec_i');
        }]);

        if (! in_array($rol, ['admin', 'recep'], true) && $usuarioSesion) {
            $query->where('d_user', $usuarioSesion);
        }

        $pacientes = $query->orderBy('pnom')->orderBy('papp')->get()->map(function (Paciente $paciente) {
            $proximaCita = $paciente->citas->first();
            $paciente->fecha_cita = $proximaCita?->fec_i;
            return $paciente;
        });

        return view('pacientes.show_pacientes', compact('pacientes', 'rol'));
    }

    public function create()
    {
        $doctores = Usuario::whereIn('rol', ['dent', 'admin'])->orderBy('nom')->get();

        return view('pacientes.regispacientes', compact('doctores'));
    }

    public function storePublico(Request $request)
    {
        // 1. Validamos estrictamente los datos personales del expediente
        $request->validate([
            'pnom'   => 'required|string|max:40',
            'papp'   => 'required|string|max:40',
            'papm'   => 'required|string|max:40',
            'pnac'   => 'required|date',
            'psex'   => 'required|string|max:1',
            'pdir'   => 'required|string|max:100',
            'pest'   => 'required|string|max:20',
            'pmun'   => 'required|string|max:25',            
            'ptel'   => 'required|string|max:10|unique:pacientes,ptel',
            'pocu'   => 'required|string|max:10',
            'pciv'   => 'required|string|max:15',
            'pcor'   => 'required|email|max:35|unique:pacientes,pcor',     
            'prel'   => 'nullable|string|max:30',
            'penv'   => 'nullable|string|max:40',
            'pmot'   => 'nullable|string|max:100',
            'd_user' => 'required|string',
            
            // Validación estricta de los antecedentes médicos y odontológicos
            'hfam'  => 'nullable|string|max:255',
            'ale'  => 'nullable|string|max:50',
            'meda'  => 'nullable|string|max:50',
            'nmed'  => 'nullable|string|max:50',
            'mtel'  => 'nullable|string|max:10',
            'c_tab'  => 'nullable|string|max:2',
            'c_alc'  => 'nullable|string|max:2',
            'c_dro'  => 'nullable|string|max:2',
            'fre'  => 'nullable|string|max:15',
            'san'  => 'nullable|string|max:3',
            'emb'  => 'nullable|string|max:2',
            'tie'  => 'nullable|string|max:10',
            'lat'  => 'nullable|string|max:2',
            'beb'  => 'nullable|string|max:2',
            'dep'  => 'nullable|string|max:20',
            'ali'  => 'nullable|string|max:10',
            'hig'  => 'nullable|string|max:10',
            'cir'  => 'nullable|string|max:2',
            'cir_des'  => 'nullable|string|max:150',
            'mot'  => 'nullable|string|max:500',
            'inte'  => 'nullable|string|max:500',
            'lab'  => 'nullable|string|max:500',
            'pes'  => 'nullable|string|max:4',
            'est'  => 'nullable|string|max:4',
            'tem'  => 'nullable|string|max:4',
            'car'  => 'nullable|string|max:4',
            'res'  => 'nullable|string|max:4',
            'pre'  => 'nullable|string|max:7',
            'ult_rev'  => 'nullable|string|max:10',
            'mot_vis'  => 'nullable|string|max:50',
            'aux_lim'  => 'nullable|string|max:2',
            'aux_cua'  => 'nullable|string|max:50',
            'cep_fre'  => 'nullable|string|max:25',
            'ane_loc' => 'nullable|string|max:2',
            'ane_com' => 'nullable|string|max:2',
            'ane_des' => 'nullable|string|max:50',
            'rem_cas' => 'nullable|string|max:2',
            'rem_des' => 'nullable|string|max:30',
            'dol_mas' => 'nullable|string|max:2',
            'dol_des' => 'nullable|string|max:20',
            'san_inf' => 'nullable|string|max:2',
            'san_des' => 'nullable|string|max:20',
            'ulc_buc' => 'nullable|string|max:2',
            'ulc_fre' => 'nullable|string|max:20',
            'hab_boc' => 'nullable|string|max:2',
            'hab_cua' => 'nullable|string|max:100',
            'obs_buc' => 'nullable|string|max:500',
            'atm_mov' => 'nullable|string|max:2',
            'atm_lat' => 'nullable|string|max:2',
            'atm_cha' => 'nullable|string|max:2',
            'atm_des' => 'nullable|string|max:2',
            'ocl_mo' => 'nullable|string|max:15',
            'ocl_ca' => 'nullable|string|max:15',
            'ocl_ovj' => 'nullable|string|max:15',
            'ocl_ovb' => 'nullable|string|max:15',
            'tej_b' => 'nullable|string|max:600'

        ]);

        // 2. Generamos el identificador único string(40) para el paciente
        $idPaciente = (string) Str::uuid();

        // 3. Guardamos el expediente en la tabla de pacientes
        Paciente::create([
            'idp'    => $idPaciente,
            'pnom'   => $request->input('pnom'),
            'papp'   => $request->input('papp'),
            'papm'   => $request->input('papm'),
            'pnac'   => $request->input('pnac'),
            'psex'   => $request->input('psex'),
            'pdir'   => $request->input('pdir'),
            'pest'   => $request->input('pest'),
            'pmun'   => $request->input('pmun'),            
            'ptel'   => $request->input('ptel'),
            'pocu'   => $request->input('pocu'),
            'pciv'   => $request->input('pciv'),
            'pcor'   => $request->input('pcor'),          
            'prel'   => $request->input('prel'),
            'penv'   => $request->input('penv'),
            'pmot'   => $request->input('pmot'),
            'd_user' => $request->input('d_user'),
            'preal'  => 'pendiente',
            

            
        ]);
        $idExpediente = (string) Str::uuid(); // Guardamos el ID del expediente para usarlo en la creación de antecedentes
        Antecedente::create([
            'idp'  => $idPaciente,
            'ide'  => $idExpediente,
            'hfam' => $request->input('hfam'),
            'fec'  => now(),
            'ale'  => $request->input('ale'),
            'meda' => $request->input('meda'),
            'nmed' => $request->input('nmed'),
            'mtel' => $request->input('mtel'),
            'c_tab'=> $request->input('c_tab'),
            'c_alc'=> $request->input('c_alc'),
            'c_dro'=> $request->input('c_dro'),
            'fre'  => $request->input('fre'),
            'san'  => $request->input('san'),
            'emb'  => $request->input('emb'),
            'tie'  => $request->input('tie'),
            'lat'  => $request->input('lat'),
            'beb'  => $request->input('beb'),
            'dep'  => $request->input('dep'),
            'ali'  => $request->input('ali'),
            'hig'  => $request->input('hig'),
            'cir'  => $request->input('cir'),
            'cir_des'=> $request->input('cir_des'),
            'mot'  => $request->input('mot'),
            'inte' => $request->input('inte'),
            'lab'  => $request->input('lab'),
            'pes'  => $request->input('pes'),
            'est'  => $request->input('est'),
            'tem'  => $request->input('tem'),
            'car'  => $request->input('car'),
            'res'  => $request->input('res'),
            'pre'  => $request->input('pre'),
            'ult_rev'  => $request->input('ult_rev'),
            'mot_vis'  => $request->input('mot_vis'),
            'aux_lim'  => $request->input('aux_lim'),
            'aux_cua'  => $request->input('aux_cua'),
            'cep_fre'  => $request->input('cep_fre'),
            'ane_loc' => $request->input('ane_loc'),
            'ane_com' => $request->input('ane_com'),
            'ane_des' => $request->input('ane_des'),
            'rem_cas' => $request->input('rem_cas'),
            'rem_des' => $request->input('rem_des'),
            'dol_mas' => $request->input('dol_mas'),
            'dol_des' => $request->input('dol_des'),
            'san_inf' => $request->input('san_inf'),
            'san_des' => $request->input('san_des'),
            'ulc_buc' => $request->input('ulc_buc'),
            'ulc_fre' => $request->input('ulc_fre'),
            'hab_boc' => $request->input('hab_boc'),
            'hab_cua' => $request->input('hab_cua'),
            'obs_buc' => $request->input('obs_buc'),
            'atm_mov' => $request->input('atm_mot'),
            'atm_lat' => $request->input('atm_lat'),
            'atm_cha' => $request->input('atm_cha'),
            'atm_des' => $request->input('atm_des'),
            'ocl_mo' => $request->input('ocl_mo'),
            'ocl_ca' => $request->input('ocl_ca'),
            'ocl_ovj' => $request->input('ocl_ovj'),
            'ocl_ovb' => $request->input('ocl_ovb'),
            'tej_b' => $request->input('tej_b')


        ]);

        // 4. ¡PASO CLAVE!: Redirigimos la petición al CitaController a través de un método de acción, inyectándole el ID generado.
        session(['agenda_id_paciente' => $idPaciente]);

        return redirect()->route('agenda.calendario');
    }
    //
}
