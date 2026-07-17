<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePersonalizado
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'No autenticado');
        }

        $rolUsuario = $user->rol instanceof \App\Enums\UserRolEnum
            ? $user->rol->value
            : (string) $user->rol;

        if ($roles !== [] && ! in_array($rolUsuario, $roles, true)) {
            abort(403, 'No tienes permisos para acceder a esta ruta.');
        }

        return $next($request);
    }
}
