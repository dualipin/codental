<?php

namespace App\Http\Controllers;

use App\Models\CatCara;
use App\Models\CatDiente;
use App\Models\CatEnfermedad;
use App\Models\DienteEstIni;
use App\Models\DienteEstTrat;
use App\Models\EnfDieIni;
use App\Models\EnfDieTrat;
use App\Models\OdontoInicial;
use App\Models\OdontoTrat;
use App\Models\Paciente;
use App\UserRolEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OdontogramaController extends Controller
{
    public function index(Request $request)
    {
        $idPac = $request->query('id_pac');
        if ($idPac) {
            session(['paciente_actual' => $idPac]);
        } else {
            $idPac = session('paciente_actual');
        }

        $pacientesDisponibles = $this->pacientesDisponibles();

        if (! $idPac) {
            return view('pacientes.odontograma', [
                'sinPaciente' => true,
                'paciente' => null,
                'pacientesDisponibles' => $pacientesDisponibles,
                'activeTab' => 'odontograma',
                'enfermedades' => collect(),
                'dientes' => collect(),
                'caras' => collect(),
                'odontoInicial' => null,
                'odontoTrat' => null,
                'registrosIniciales' => [],
                'registrosTratamiento' => [],
            ]);
        }

        $paciente = Paciente::findOrFail($idPac);
        $enfermedades = CatEnfermedad::orderBy('nom')->get();
        $dientes = CatDiente::orderBy('num_fdi')->get();
        $caras = CatCara::orderBy('id_cara')->get();

        $odontoInicial = OdontoInicial::with(['dientes.enfermedades.enfermedad', 'paciente'])
            ->where('id_pac', $idPac)
            ->first();

        $odontoTrat = OdontoTrat::with(['dientes.enfermedades.enfermedad', 'paciente'])
            ->where('id_pac', $idPac)
            ->first();

        $registrosIniciales = $this->mapRegistrosIniciales($odontoInicial);
        $registrosTratamiento = $this->mapRegistrosTratamiento($odontoTrat);

        return view('pacientes.odontograma', array_merge(
            compact(
                'paciente',
                'pacientesDisponibles',
                'enfermedades',
                'dientes',
                'caras',
                'odontoInicial',
                'odontoTrat',
                'registrosIniciales',
                'registrosTratamiento'
            ),
            ['sinPaciente' => false, 'activeTab' => 'odontograma']
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pac' => ['required', 'exists:pacientes,idp'],
            'fase' => ['required', 'in:inicial,tratamiento'],
            'registros_json' => ['required', 'string'],
            'obs' => ['nullable', 'string', 'max:500'],
        ]);

        $paciente = Paciente::findOrFail($request->input('id_pac'));
        $registros = json_decode($request->input('registros_json'), true) ?: [];

        if (empty($registros)) {
            return back()->withErrors(['registros_json' => 'Agrega al menos un registro al odontograma.']);
        }

        DB::transaction(function () use ($request, $paciente, $registros) {
            if ($request->input('fase') === 'inicial') {
                $odontoInicial = OdontoInicial::firstOrCreate(
                    ['id_pac' => $paciente->idp],
                    ['fec_reg' => Carbon::today(), 'obs' => $request->input('obs')]
                );

                $this->replaceInitialRecords($odontoInicial, $registros);
                $this->syncTreatmentFromInitial($paciente, $odontoInicial);
                return;
            }

            $odontoInicial = OdontoInicial::where('id_pac', $paciente->idp)->first();
            if (! $odontoInicial) {
                abort(422, 'Primero se debe registrar el odontograma inicial.');
            }

            $odontoTrat = OdontoTrat::firstOrCreate(
                ['id_pac' => $paciente->idp],
                [
                    'id_odoi' => $odontoInicial->id_odoi,
                    'fec_ult' => Carbon::today(),
                    'ver' => 1,
                    'act' => true,
                    'obs' => $request->input('obs'),
                ]
            );

            $odontoTrat->update([
                'id_odoi' => $odontoInicial->id_odoi,
                'fec_ult' => Carbon::today(),
                'act' => true,
                'obs' => $request->input('obs'),
                'ver' => (int) ($odontoTrat->ver ?? 1) + 1,
            ]);

            $this->replaceTreatmentRecords($odontoInicial, $odontoTrat, $registros);
        });

        return back()->with('success', 'Odontograma guardado correctamente.');
    }

    private function mapRegistrosIniciales(?OdontoInicial $odontoInicial): array
    {
        if (! $odontoInicial) {
            return [];
        }

        return $odontoInicial->dientes->flatMap(function (DienteEstIni $diente) {
            return $diente->enfermedades->map(function (EnfDieIni $enf) use ($diente) {
                return [
                    'diente' => $diente->diente?->num_fdi,
                    'cara' => $diente->cara?->abr,
                    'enfermedad' => $enf->enfermedad?->nom,
                    'color' => $enf->enfermedad?->color,
                    'obs' => $enf->obs,
                ];
            });
        })->values()->all();
    }

    private function mapRegistrosTratamiento(?OdontoTrat $odontoTrat): array
    {
        if (! $odontoTrat) {
            return [];
        }

        return $odontoTrat->dientes->flatMap(function (DienteEstTrat $diente) {
            return $diente->enfermedades->map(function (EnfDieTrat $enf) use ($diente) {
                return [
                    'diente' => $diente->diente?->num_fdi,
                    'cara' => $diente->cara?->abr,
                    'enfermedad' => $enf->enfermedad?->nom,
                    'color' => $enf->enfermedad?->color,
                    'obs' => $enf->obs,
                ];
            });
        })->values()->all();
    }

    private function replaceInitialRecords(OdontoInicial $odontoInicial, array $registros): void
    {
        $odontoInicial->dientes()->delete();

        foreach ($registros as $registro) {
            $diente = CatDiente::where('num_fdi', (int) $registro['diente'])->first();
            $cara = CatCara::where('abr', $registro['cara'])->first();
            $enfermedad = CatEnfermedad::find($registro['id_enf']);

            if (! $diente || ! $cara || ! $enfermedad) {
                continue;
            }

            $estadoInicial = DienteEstIni::create([
                'id_odoi' => $odontoInicial->id_odoi,
                'id_die' => $diente->id_diente,
                'id_car' => $cara->id_cara,
            ]);

            EnfDieIni::create([
                'id_dei' => $estadoInicial->id_dei,
                'id_enf' => $enfermedad->id_enf,
                'obs' => $registro['obs'] ?? null,
            ]);
        }
    }

    private function syncTreatmentFromInitial(Paciente $paciente, OdontoInicial $odontoInicial): void
    {
        $odontoTrat = OdontoTrat::firstOrCreate(
            ['id_pac' => $paciente->idp],
            [
                'id_odoi' => $odontoInicial->id_odoi,
                'fec_ult' => Carbon::today(),
                'ver' => 1,
                'act' => true,
                'obs' => $odontoInicial->obs,
            ]
        );

        $odontoTrat->update([
            'id_odoi' => $odontoInicial->id_odoi,
            'fec_ult' => Carbon::today(),
            'act' => true,
            'obs' => $odontoInicial->obs,
        ]);

        $this->replaceTreatmentRecordsFromInitial($odontoInicial, $odontoTrat);
    }

    private function replaceTreatmentRecordsFromInitial(OdontoInicial $odontoInicial, OdontoTrat $odontoTrat): void
    {
        $odontoTrat->dientes()->delete();

        foreach ($odontoInicial->dientes as $dienteIni) {
            $estadoTrat = DienteEstTrat::create([
                'id_odot' => $odontoTrat->id_odot,
                'id_die' => $dienteIni->id_die,
                'id_car' => $dienteIni->id_car,
                'est' => 'Pendiente',
                'fec_ini' => Carbon::today(),
            ]);

            foreach ($dienteIni->enfermedades as $enfIni) {
                EnfDieTrat::create([
                    'id_det' => $estadoTrat->id_det,
                    'id_enf' => $enfIni->id_enf,
                    'id_edi' => $enfIni->id_edi,
                    'fec_dia' => Carbon::today(),
                    'est' => 'Activa',
                    'obs' => $enfIni->obs,
                ]);
            }
        }
    }

    private function replaceTreatmentRecords(OdontoInicial $odontoInicial, OdontoTrat $odontoTrat, array $registros): void
    {
        $odontoTrat->dientes()->delete();

        foreach ($registros as $registro) {
            $diente = CatDiente::where('num_fdi', (int) $registro['diente'])->first();
            $cara = CatCara::where('abr', $registro['cara'])->first();
            $enfermedad = CatEnfermedad::find($registro['id_enf']);

            if (! $diente || ! $cara || ! $enfermedad) {
                continue;
            }

            $estadoTrat = DienteEstTrat::create([
                'id_odot' => $odontoTrat->id_odot,
                'id_die' => $diente->id_diente,
                'id_car' => $cara->id_cara,
                'est' => $registro['est'] ?? 'Pendiente',
                'fec_ini' => Carbon::today(),
                'fec_com' => in_array($registro['est'] ?? 'Pendiente', ['Completado'], true) ? Carbon::today() : null,
            ]);

            $estadoInicial = DienteEstIni::where('id_odoi', $odontoInicial->id_odoi)
                ->where('id_die', $diente->id_diente)
                ->where('id_car', $cara->id_cara)
                ->first();

            EnfDieTrat::create([
                'id_det' => $estadoTrat->id_det,
                'id_enf' => $enfermedad->id_enf,
                'id_edi' => $estadoInicial?->id_dei,
                'fec_dia' => Carbon::today(),
                'est' => 'Activa',
                'obs' => $registro['obs'] ?? null,
            ]);
        }
    }

    private function pacientesDisponibles()
    {
        $rol = session('rol');
        $usuarioSesion = session('usuario_model') ?: session('id_usuario') ?: session('usuario');

        $query = Paciente::query()->orderBy('pnom')->orderBy('papp');

        if (! in_array($rol, [UserRolEnum::ADMINISTRADOR->value, UserRolEnum::RECEPCIONISTA->value], true) && $usuarioSesion) {
            $query->where('d_user', $usuarioSesion);
        }

        return $query->get(['idp', 'pnom', 'papp', 'papm']);
    }
}
