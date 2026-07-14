<?php

namespace App\Http\Controllers;

use App\Models\Credenciale;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => ['required', 'string'],
            'contrasena' => ['required', 'string'],
        ]);

        $credencial = Credenciale::where('name', $request->input('usuario'))
            ->where('pass', $request->input('contrasena'))
            ->with('usuario')
            ->first();

        if (! $credencial) {
            return back()->withErrors(['error' => 'Usuario o contraseña incorrectos.']);
        }

        $usuario = $credencial->usuario;

        session([
            'usuario' => $credencial->name,
            'id_usuario' => $credencial->user,
            'nom' => trim(($usuario?->nom ?? '') . ' ' . ($usuario?->app ?? '') . ' ' . ($usuario?->apm ?? '')) ?: $credencial->name,
            'rol' => $usuario?->rol,
            'usuario_model' => $usuario?->user,
        ]);

        return redirect()->route('agenda');
    }

    public function logout(Request $request)
    {
        session()->forget(['usuario', 'id_usuario', 'nom', 'rol', 'usuario_model', 'agenda_id_paciente']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
