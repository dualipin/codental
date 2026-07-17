<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function view;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('app.users.index', ['usuarios' => $usuarios]);
    }

    public function create()
    {
        return view('app.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'telefono' => 'required|string|max:10',
            'sexo' => 'required|in:M,F,O',
            'fecha_nacimiento' => 'required|date',
            'rol' => 'required|in:dent,recep,admin',
            'especialidad' => 'nullable|string|max:255',
            'estado_civil' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'cedula' => 'nullable|string|max:255',
            'foto_usuario' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('foto_usuario')) {
            $validated['foto_usuario'] = $request->file('foto_usuario')->store('fotos_usuarios', 'public');
        }

        User::create($validated);

        return redirect()->route('usuarios')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(int $id)
    {
        $usuario = User::find($id);
        return view('app.users.edit', ['usuario' => $usuario]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'required|string|max:10',
            'sexo' => 'required|in:M,F,O',
            'fecha_nacimiento' => 'required|date',
            'rol' => 'required|in:dent,recep,admin',
            'especialidad' => 'nullable|string|max:255',
            'estado_civil' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'cedula' => 'nullable|string|max:255',
            'foto_usuario' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = $request->password;
        }

        if ($request->hasFile('foto_usuario')) {
            if ($user->foto_usuario) {
                Storage::disk('public')->delete($user->foto_usuario);
            }
            $validated['foto_usuario'] = $request->file('foto_usuario')->store('fotos_usuarios', 'public');
        }

        $user->update($validated);

        return redirect()->route('usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->foto_usuario) {
            Storage::disk('public')->delete($user->foto_usuario);
        }

        $user->delete();

        return redirect()->route('usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    public function profile(int $id)
    {
        $usuario = User::findOrFail($id);
        return view('app.users.profile', ['usuario' => $usuario]);
    }

    public function settings(int $id)
    {
        $usuario = User::findOrFail($id);

        return view('app.users.settings', ['usuario' => $usuario]);
    }

    public function updateSettings(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('usuarios.settings', $user)->with('success', 'Contraseña actualizada correctamente.');
    }
}
