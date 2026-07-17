<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinica;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

use function session;


class PacienteController extends Controller
{
    function index(Request $request)
    {
        $query = $request->get('q');

        $pacientes = Paciente::when($query, function ($q) use ($query) {
            $q->where('nombre', 'like', "%{$query}%")
              ->orWhere('apellido_paterno', 'like', "%{$query}%")
              ->orWhere('apellido_materno', 'like', "%{$query}%")
              ->orWhere('telefono', 'like', "%{$query}%");
        })->get();

        return view('app.pacientes.index', ['pacientes' => $pacientes, 'query' => $query]);
    }

    function create()
    {
        return Inertia::render('Pacientes/Create');
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:10|unique:pacientes,telefono',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F,O',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'estado_civil' => 'nullable|string|max:20',
            'correo_electronico' => 'nullable|email|max:255',
            'religion' => 'nullable|string|max:255',
            'antecedentes_hereditarios' => 'nullable|string',
            'alergias' => 'nullable|string',
            'medicacion_actual' => 'nullable|string',
            'nombre_medico' => 'nullable|string|max:150',
            'telefono_medico' => 'nullable|string|max:20',
            'enfermedades_previas' => 'nullable|array',
            'enfermedades_previas.*' => 'string|max:100',
            'otras_enfermedades' => 'nullable|string',
            'habitos_toxicos' => 'nullable|array',
            'habitos_toxicos.tabaco' => 'nullable|boolean',
            'habitos_toxicos.alcohol' => 'nullable|boolean',
            'habitos_toxicos.drogas' => 'nullable|boolean',
            'habitos_toxicos.frecuenciaConsumo' => 'nullable|string|max:100',
            'grupo_sanguineo' => 'nullable|string|max:10',
            'ginecoobstetricos' => 'nullable|array',
            'ginecoobstetricos.embarazo' => 'nullable|boolean',
            'ginecoobstetricos.tiempoGestacion' => 'nullable|string|max:100',
            'ginecoobstetricos.lactancia' => 'nullable|boolean',
            'ginecoobstetricos.mesesBebe' => 'nullable|string|max:100',
            'estilo_vida' => 'nullable|array',
            'estilo_vida.actividadFisica' => 'nullable|string|max:100',
            'estilo_vida.calidadDieta' => 'nullable|string|max:100',
            'estilo_vida.calidadHigiene' => 'nullable|string|max:100',
            'cirugias_hospitalizaciones' => 'nullable|string',
            'padecimiento_actual' => 'nullable|string',
            'interrogatorio_sistemas' => 'nullable|string',
            'examenes_laboratorio' => 'nullable|string',
            'antecedentes_bucodentales' => 'nullable|array',
            'atm' => 'nullable|array',
            'tejidos_blandos_duros' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $paciente = Paciente::create([
                'nombre' => $validated['nombre'],
                'apellido_paterno' => $validated['apellido_paterno'],
                'apellido_materno' => $validated['apellido_materno'] ?? null,
                'telefono' => $validated['telefono'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'sexo' => $validated['sexo'],
                'direccion' => $validated['direccion'] ?? null,
                'estado' => $validated['estado'] ?? null,
                'municipio' => $validated['municipio'] ?? null,
                'ocupacion' => $validated['ocupacion'] ?? null,
                'estado_civil' => $validated['estado_civil'] ?? null,
                'correo_electronico' => $validated['correo_electronico'] ?? null,
                'religion' => $validated['religion'] ?? null,
            ]);

            $habitos = $validated['habitos_toxicos'] ?? [];
            $gineco = $validated['ginecoobstetricos'] ?? [];
            $estiloVida = $validated['estilo_vida'] ?? [];

            HistoriaClinica::create([
                'paciente_id' => $paciente->id,
                'antecedentes_hereditarios' => $validated['antecedentes_hereditarios'] ?? null,
                'alergias' => $validated['alergias'] ?? null,
                'medicacion_actual' => $validated['medicacion_actual'] ?? null,
                'nombre_medico' => $validated['nombre_medico'] ?? null,
                'telefono_medico' => $validated['telefono_medico'] ?? null,
                'enfermedades_previas' => array_values($validated['enfermedades_previas'] ?? []),
                'otras_enfermedades' => $validated['otras_enfermedades'] ?? null,
                'habitos_toxicos' => [
                    'tabaco' => (bool) ($habitos['tabaco'] ?? false),
                    'alcohol' => (bool) ($habitos['alcohol'] ?? false),
                    'drogas' => (bool) ($habitos['drogas'] ?? false),
                    'frecuenciaConsumo' => $habitos['frecuenciaConsumo'] ?? '',
                ],
                'grupo_sanguineo' => $validated['grupo_sanguineo'] ?? null,
                'ginecoobstetricos' => [
                    'embarazo' => (bool) ($gineco['embarazo'] ?? false),
                    'tiempoGestacion' => $gineco['tiempoGestacion'] ?? '',
                    'lactancia' => (bool) ($gineco['lactancia'] ?? false),
                    'mesesBebe' => $gineco['mesesBebe'] ?? '',
                ],
                'estilo_vida' => [
                    'actividadFisica' => $estiloVida['actividadFisica'] ?? '',
                    'calidadDieta' => $estiloVida['calidadDieta'] ?? '',
                    'calidadHigiene' => $estiloVida['calidadHigiene'] ?? '',
                ],
                'cirugias_hospitalizaciones' => $validated['cirugias_hospitalizaciones'] ?? null,
                'padecimiento_actual' => $validated['padecimiento_actual'] ?? null,
                'interrogatorio_sistemas' => $validated['interrogatorio_sistemas'] ?? null,
                'examenes_laboratorio' => $validated['examenes_laboratorio'] ?? null,
                'antecedentes_bucodentales' => $validated['antecedentes_bucodentales'] ?? [],
                'atm' => $validated['atm'] ?? [],
                'tejidos_blandos_duros' => $validated['tejidos_blandos_duros'] ?? null,
            ]);
        });

        session()->flash('success', 'Paciente creado correctamente.');

        return Inertia::location(route('pacientes.index'));
    }

    function show(Paciente $paciente)
    {
        $paciente->load([
            'historiaClinica',
            'citas' => fn ($q) => $q->with('dentista')->latest('fecha_inicio'),
            'consultas' => fn ($q) => $q->with('odontologo')->latest('fecha_consulta'),
            'presupuestos' => fn ($q) => $q->with(['dentista', 'detalles.distribuciones'])->latest('fecha_emision'),
        ]);

        return view('app.pacientes.show', ['paciente' => $paciente]);
    }

    function edit(Paciente $paciente)
    {
        return view('app.pacientes.edit', ['paciente' => $paciente]);
    }

    function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => ['required', 'string', 'max:10', Rule::unique('pacientes', 'telefono')->ignore($paciente)],
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F,O',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'estado_civil' => 'nullable|string|max:20',
            'correo_electronico' => 'nullable|email|max:255',
            'religion' => 'nullable|string|max:255',
        ]);

        $paciente->update($validated);

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado correctamente.');
    }

    function verify(Paciente $paciente)
    {
        $paciente->update(['verificado' => !$paciente->verificado]);

        $mensaje = $paciente->verificado ? 'Paciente verificado correctamente.' : 'Verificación removida correctamente.';
        return back()->with('success', $mensaje);
    }
}
