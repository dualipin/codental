<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
       View::composer('*', function ($view) {
        // 1. Buscamos el usuario en TU sesión manual. Si no hay, es 'Invitado'
        $usuario = session('usuario', 'Invitado');
        
        // 2. Buscamos el nombre real. Si no existe, usamos el usuario como respaldo
        $nom = session('nom') ?: $usuario;
        $rol = session('rol');
        
        // 3. Compartimos las variables con TODAS las vistas
        $view->with('nom', $nom);
        $view->with('rol', $rol);
    });

    }
}
