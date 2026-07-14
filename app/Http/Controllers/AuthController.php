<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $usuario = User::where('email', $credentials['email'])->first();

        if (! $usuario || ! Hash::check($credentials['password'], $usuario->password)) {
            return back()->withErrors(['error' => 'Usuario o contraseña incorrectos.']);
        }

        session([
            'usuario' => $usuario->email,
            'id_usuario' => $usuario->id,
            'nom' => trim("{$usuario->nombre} {$usuario->apellido_paterno} {$usuario->apellido_materno}"),
            'rol' => $usuario->rol->value,
            'usuario_model' => $usuario->id,
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
