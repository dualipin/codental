<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\CajaPac;
use App\Models\Paciente;
use App\Models\PacTratSel;
use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    public function index(Request $request)
    {
        $rol = session('rol');
        $puedeRegistrar = $rol === 'recep';

        $queryPacientes = Paciente::query()->orderBy('pnom')->orderBy('papp');
        if (! in_array($rol, ['admin', 'recep'], true)) {
            $usuarioSesion = session('usuario_model') ?: session('id_usuario') ?: session('usuario');
            $queryPacientes->where('d_user', $usuarioSesion);
        }

        $pacientes = $queryPacientes->get();
        $idPac = $request->query('id_pac');

        if ($idPac) {
            session(['paciente_actual' => $idPac]);
        } else {
            $idPac = session('paciente_actual') ?: $idPac;
        }

        if (! $idPac && $pacientes->isNotEmpty()) {
            $idPac = $pacientes->first()->idp;
        }

        if (! $idPac) {
            return view('facturacion', [
                'rol' => $rol,
                'puedeRegistrar' => $puedeRegistrar,
                'pacientes' => $pacientes,
                'paciente' => null,
                'caja' => null,
                'tratamientos' => collect(),
                'abonos' => collect(),
                'selectedIdPac' => null,
                'activeTab' => 'facturacion',
                'pacientesDisponibles' => $pacientes,
            ]);
        }

        $paciente = Paciente::findOrFail($idPac);
        $caja = CajaPac::firstOrCreate(['id_pac' => $idPac]);
        $caja->actualizarSaldo();

        $tratamientos = PacTratSel::with(['tratamiento', 'precio'])
            ->where('id_pac', $idPac)
            ->orderByDesc('fec_sel')
            ->get();

        $abonos = Abono::with(['tratamientoSeleccionado', 'usuarioRegistro'])
            ->where('id_pac', $idPac)
            ->orderByDesc('fec_abo')
            ->get();

        return view('facturacion', array_merge(
            compact(
                'rol',
                'puedeRegistrar',
                'pacientes',
                'paciente',
                'caja',
                'tratamientos',
                'abonos',
                'idPac'
            ),
            [
                'activeTab' => 'facturacion',
                'pacientesDisponibles' => $pacientes,
            ]
        ));
    }

    public function registrarAbono(Request $request)
    {
        $this->authorizeRecep();

        $request->validate([
            'id_pac' => ['required', 'exists:pacientes,idp'],
            'id_pts' => ['nullable', 'exists:pac_trat_sel,id_pts'],
            'mon' => ['required', 'numeric', 'min:0.01'],
            'fec_abo' => ['required', 'date'],
            'met_pag' => ['required', 'string', 'max:30'],
            'ref' => ['nullable', 'string', 'max:80'],
            'obs' => ['nullable', 'string', 'max:250'],
            'des_apl' => ['nullable', 'numeric', 'min:0'],
        ]);

        Abono::create([
            'id_pac' => $request->input('id_pac'),
            'id_pts' => $request->input('id_pts'),
            'mon' => $request->input('mon'),
            'fec_abo' => $request->input('fec_abo'),
            'met_pag' => $request->input('met_pag'),
            'ref' => $request->input('ref'),
            'obs' => $request->input('obs'),
            'des_apl' => $request->input('des_apl', 0),
            'est' => 'Activo',
            'id_usuario_registro' => session('usuario_model') ?: session('usuario'),
        ]);

        $caja = CajaPac::firstOrCreate(['id_pac' => $request->input('id_pac')]);
        $caja->actualizarSaldo();

        return back()->with('success', 'Abono registrado correctamente.');
    }

    public function actualizarAbono(Request $request, Abono $abono)
    {
        $this->authorizeRecep();

        $request->validate([
            'mon' => ['required', 'numeric', 'min:0.01'],
            'fec_abo' => ['required', 'date'],
            'met_pag' => ['required', 'string', 'max:30'],
            'ref' => ['nullable', 'string', 'max:80'],
            'obs' => ['nullable', 'string', 'max:250'],
            'des_apl' => ['nullable', 'numeric', 'min:0'],
        ]);

        $abono->update([
            'mon' => $request->input('mon'),
            'fec_abo' => $request->input('fec_abo'),
            'met_pag' => $request->input('met_pag'),
            'ref' => $request->input('ref'),
            'obs' => $request->input('obs'),
            'des_apl' => $request->input('des_apl', 0),
        ]);

        $caja = CajaPac::firstOrCreate(['id_pac' => $abono->id_pac]);
        $caja->actualizarSaldo();

        return back()->with('success', 'Abono actualizado correctamente.');
    }

    public function anularAbono(Request $request, Abono $abono)
    {
        $this->authorizeRecep();

        $request->validate([
            'mot_anulacion' => ['required', 'string', 'max:250'],
        ]);

        $abono->update([
            'est' => 'Anulado',
            'mot_anulacion' => $request->input('mot_anulacion'),
        ]);

        $caja = CajaPac::firstOrCreate(['id_pac' => $abono->id_pac]);
        $caja->actualizarSaldo();

        return back()->with('success', 'Abono anulado correctamente.');
    }

    private function authorizeRecep(): void
    {
        if (session('rol') !== 'recep') {
            abort(403, 'Solo recepción puede registrar o modificar pagos.');
        }
    }
}
