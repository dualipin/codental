<?php

namespace App\Http\Controllers;

use App\Enums\UserRolEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function auth;

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

        if (!$usuario || !Hash::check($credentials['password'], $usuario->password)) {
            return back()->withErrors(['error' => 'Usuario o contraseña incorrectos.']);
        }

        auth()->login($usuario);

        if (auth()->user()->rol == UserRolEnum::RECEPCIONISTA) {
            return redirect()->route('recepcion.dashboard');
        }

        return redirect()->route('agenda');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
