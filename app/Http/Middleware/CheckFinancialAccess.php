<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFinancialAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'No autenticado');
        }

        $method = $request->getMethod();
        $isWriteMethod = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']);

        // Bloquear escritura a Médicos (Dentistas) en rutas financieras
        if ($isWriteMethod && $user->rol === \App\Enums\UserRolEnum::DENTISTA->value) {
            abort(403, 'Los médicos no tienen permisos para modificar registros financieros.');
        }

        // Bloquear anulación de pagos si no es Administrador
        if ($request->routeIs('*.anular') || str_contains($request->path(), 'anular')) {
            if ($user->rol !== \App\Enums\UserRolEnum::ADMINISTRADOR->value) {
                abort(403, 'Solo un Administrador puede anular pagos (can_void_payments).');
            }
        }

        return $next($request);
    }
}
