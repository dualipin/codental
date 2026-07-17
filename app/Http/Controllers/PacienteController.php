<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


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
        return view('app.pacientes.create');
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
        ]);

        Paciente::create($validated);

        return redirect()->route('pacientes.index')->with('success', 'Paciente creado correctamente.');
    }

    function show(Paciente $paciente)
    {
        $paciente->load([
            'historiaClinica',
            'citas' => fn ($q) => $q->with('dentista')->latest('fecha_inicio'),
            'consultas' => fn ($q) => $q->with('odontologo')->latest('fecha_consulta'),
            'presupuestos' => fn ($q) => $q->with('dentista', 'abonos')->latest('fecha_emision'),
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
}
