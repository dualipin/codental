<?php

namespace App\Http\Controllers;

use App\Enums\UserRolEnum;
use App\Models\Antecedente;
use App\Models\CajaPac;
use App\Models\OdontoInicial;
use App\Models\OdontoTrat;
use App\Models\Paciente;
use App\Models\PacTratSel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FichaPacienteController extends Controller
{
    public function seleccionar(string $idPac)
    {
        session(['paciente_actual' => $idPac]);

        return redirect()->route('pacientes.personal', ['id_pac' => $idPac]);
    }

    public function personal(Request $request)
    {
        $context = $this->resolverContexto($request);

        return view('pacientes.personal', array_merge($context, [
            'activeTab' => 'personal',
        ]));
    }

    public function datos(Request $request)
    {
        $context = $this->resolverContexto($request);

        return view('datos', array_merge($context, [
            'activeTab' => 'datos',
        ]));
    }

    public function antecedentes(Request $request)
    {
        $context = $this->resolverContexto($request);

        return view('pacientes.antecedentes', array_merge($context, [
            'activeTab' => 'antecedentes',
        ]));
    }

    public function planTratamiento(Request $request)
    {
        $context = $this->resolverContexto($request);

        $tratamientos = PacTratSel::with(['tratamiento', 'precio', 'abonos'])
            ->where('id_pac', $context['paciente']->idp)
            ->orderByDesc('fec_sel')
            ->get();

        $caja = CajaPac::firstOrCreate(['id_pac' => $context['paciente']->idp]);
        $caja->actualizarSaldo();

        return view('plan_tratamiento', array_merge($context, [
            'activeTab' => 'plan',
            'tratamientos' => $tratamientos,
            'caja' => $caja,
        ]));
    }

    public function facturacion(Request $request)
    {
        $context = $this->resolverContexto($request);

        return redirect()->route('caja.facturacion', [
            'id_pac' => $context['paciente']->idp,
        ]);
    }

    public function actualizarDatos(Request $request, string $idPac)
    {
        $paciente = Paciente::findOrFail($idPac);

        $request->validate([
            'pnom' => ['required', 'string', 'max:40'],
            'papp' => ['required', 'string', 'max:40'],
            'papm' => ['required', 'string', 'max:40'],
            'pnac' => ['required', 'date'],
            'psex' => ['required', 'string', 'max:1'],
            'pdir' => ['required', 'string', 'max:100'],
            'pest' => ['required', 'string', 'max:20'],
            'pmun' => ['required', 'string', 'max:25'],
            'ptel' => ['required', 'string', 'max:10'],
            'pocu' => ['required', 'string', 'max:10'],
            'pciv' => ['required', 'string', 'max:15'],
            'pcor' => ['required', 'email', 'max:35'],
            'prel' => ['nullable', 'string', 'max:30'],
            'penv' => ['nullable', 'string', 'max:40'],
            'pmot' => ['nullable', 'string', 'max:100'],
        ]);

        $paciente->update($request->only([
            'pnom', 'papp', 'papm', 'pnac', 'psex', 'pdir', 'pest', 'pmun',
            'ptel', 'pocu', 'pciv', 'pcor', 'prel', 'penv', 'pmot'
        ]) + ['preal' => 'confirmado']);

        session(['paciente_actual' => $paciente->idp]);

        return back()->with('success', 'Datos del paciente actualizados.');
    }

    public function actualizarAntecedentes(Request $request, string $idPac)
    {
        $paciente = Paciente::findOrFail($idPac);

        $request->validate([
            'hfam' => ['nullable', 'string', 'max:255'],
            'ale' => ['nullable', 'string', 'max:50'],
            'meda' => ['nullable', 'string', 'max:50'],
            'nmed' => ['nullable', 'string', 'max:50'],
            'mtel' => ['nullable', 'string', 'max:10'],
            'san' => ['nullable', 'string', 'max:3'],
            'obs_buc' => ['nullable', 'string', 'max:500'],
            'mot' => ['nullable', 'string', 'max:500'],
            'inte' => ['nullable', 'string', 'max:500'],
            'lab' => ['nullable', 'string', 'max:500'],
        ]);

        $antecedente = Antecedente::firstOrNew(['idp' => $paciente->idp]);

        if (! $antecedente->exists) {
            $antecedente->ide = (string) \Illuminate\Support\Str::uuid();
            $antecedente->fec = Carbon::now();
        }

        $antecedente->fill($request->only([
            'hfam', 'ale', 'meda', 'nmed', 'mtel', 'san', 'obs_buc', 'mot', 'inte', 'lab',
        ]));
        $antecedente->save();

        session(['paciente_actual' => $paciente->idp]);

        return back()->with('success', 'Antecedentes actualizados correctamente.');
    }

    private function resolverContexto(Request $request): array
    {
        $idPac = $request->query('id_pac') ?: session('paciente_actual');

        if (! $idPac) {
            abort(403, 'Selecciona un paciente primero.');
        }

        $paciente = Paciente::with(['citas' => function ($query) {
            $query->orderByDesc('fec_i');
        }])->findOrFail($idPac);

        $antecedente = Antecedente::where('idp', $idPac)->orderByDesc('fec')->first();
        $odontoInicial = OdontoInicial::where('id_pac', $idPac)->first();
        $odontoTrat = OdontoTrat::where('id_pac', $idPac)->first();
        $caja = CajaPac::firstOrCreate(['id_pac' => $idPac]);
        $caja->actualizarSaldo();

        $ultimaCita = $paciente->citas->first();

        $rol = session('rol');
        $usuarioSesion = session('usuario_model') ?: session('id_usuario') ?: session('usuario');
        $queryPacientes = Paciente::query()->orderBy('pnom')->orderBy('papp');
        if (! in_array($rol, [UserRolEnum::ADMINISTRADOR->value, UserRolEnum::RECEPCIONISTA->value], true) && $usuarioSesion) {
            $queryPacientes->where('d_user', $usuarioSesion);
        }
        $pacientesDisponibles = $queryPacientes->get(['idp', 'pnom', 'papp', 'papm']);

        return [
            'paciente' => $paciente,
            'antecedente' => $antecedente,
            'odontoInicialExiste' => (bool) $odontoInicial,
            'odontoTratExiste' => (bool) $odontoTrat,
            'ultimaCita' => $ultimaCita,
            'caja' => $caja,
            'pacientesDisponibles' => $pacientesDisponibles,
        ];
    }
}
