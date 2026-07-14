<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthPersonalizado
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Condicional de sesion para verificar si el usuario esta autenticado
        if(!session()->has('usuario')) {
            return redirect()->route('nosotros')->with('error', 'Acceso denegado. Debes iniciar sesión');
        }
        return $next($request);
    }
}
