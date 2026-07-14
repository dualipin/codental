<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Credenciale;
use Illuminate\Support\Facades\DB;

class RegisuserController extends Controller
{
    public function create()
    {
        // Validación de rol basada en tu sesión actual
        if (session('rol') !== 'admin') {
            return redirect('/inicio')->withErrors(['error' => 'No tienes permisos para registrar usuarios.']);
        }
        return view('/admin/registro_usuario');
    }

    public function store(Request $request)
    {
        if (session('rol') !== 'admin') {
            return abort(403, 'Acción no autorizada.');
        }

        // VALIDACIÓN ESTRICTA AJUSTADA A TUS LÍMITES DE MIGRACIÓN
        $request->validate([
            'nom' => 'required|string|max:20',
            'app' => 'required|string|max:20',
            'apm' => 'required|string|max:20', // Obligatorio según tu migración
            'sex' => 'required|string|max:1',   // 'M' o 'F' (1 carácter)
            'ced' => 'nullable|string|max:8|unique:usuarios,ced',
            'esp' => 'required|string|max:15',  // Especialidad max 15
            'nac' => 'required|date',
            'civ' => 'required|string|max:10',
            'dic' => 'required|string|max:50',
            'est' => 'required|string|max:15',  // Estado (p. ej. 'activo')
            'mun' => 'required|string|max:20',
            'tel' => 'required|string|max:10',
            'cor' => 'required|email|max:35|unique:usuarios,cor',
            'rol' => 'required|string|max:5',   // Ajustado a máximo 5 caracteres (ej: 'admin', 'dent')
            
            // Datos para la tabla Credenciales
            'usuario_login' => 'required|string|max:30|unique:credenciales,name',
            'contrasena_login' => 'required|string|min:6'
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear el Usuario (Genera automáticamente el UUID de 40 caracteres en 'user')
            $nuevoUsuario = Usuario::create([
                
                'nom' => $request->input('nom'),
                'app' => $request->input('app'),
                'apm' => $request->input('apm'),
                'sex' => $request->input('sex'),
                'ced' => $request->input('ced'),
                'esp' => $request->input('esp'),
                'nac' => $request->input('nac'),
                'civ' => $request->input('civ'),
                'dic' => $request->input('dic'),
                'est' => $request->input('est'), 
                'mun' => $request->input('mun'),
                'tel' => $request->input('tel'),
                'cor' => $request->input('cor'),
                'rol' => $request->input('rol'), // Mandará el valor corto (ej: 'dent')
                'fec' => now() // Setea el TIMESTAMP actual
            ]);

            // 2. Crear la Credencial vinculada
            Credenciale::create([
                'user' => $nuevoUsuario->user, // Llave primaria de 40 caracteres creada arriba
                'name' => $request->input('usuario_login'),
                // Guardamos la contraseña (puedes usar password_hash o dejarla en texto plano si tu login lee texto plano)
                'pass' => $request->input('contrasena_login'), 
                'tok'  => null,
                'tim'  => null
            ]);

            DB::commit();

            return redirect('/inicio')->with('success', 'Usuario registrado con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al registrar: ' . $e->getMessage()]);
        }
    }
    public function showUsuarios()
    {
        if (session('rol') !== 'admin') {
            return abort(403, 'Acción no autorizada.');
        }

        $usuarios = Usuario::all();
        return view('admin.show_usuario', compact('usuarios'));
    }
}
