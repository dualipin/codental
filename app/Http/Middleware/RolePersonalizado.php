<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePersonalizado
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!session()->has('usuario')) {
            return redirect()->route('nosotros')->with('error', 'Acceso denegado. Debes iniciar sesión');
        }

        if (!in_array(session('rol'), $roles, true)) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}