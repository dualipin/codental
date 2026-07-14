<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            //'user' como string y clave primaria
            $table->string('user', 40)->primary()->comment('usuario');
            
            // Campos requeridos (NOT NULL)
            $table->string('nom', 20)->comment('nombre');
            $table->string('app', 20)->comment('apellido paterno');
            $table->string('apm', 20)->comment('apellido materno');
            $table->string('sex', 1)->comment('sexo');
            $table->string('ced', 8)->nullable()->comment('cedula'); //excepcion nullable
            $table->string('esp', 15)->comment('especialidad');
            $table->date('nac')->comment('fecha de nacimiento');
            $table->string('civ', 10)->comment('estado civil');
            $table->string('dic', 50)->comment('direccion');
            $table->string('est', 15)->comment('estado');
            $table->string('mun', 20)->comment('municipio');
            $table->string('tel', 10)->comment('telefono');
            $table->string('cor', 35)->comment('correo');
            $table->string('rol', 5)->comment('roles');

            // Campos opcionales (NULL)
            $table->binary('img')->nullable()->comment('foto_usuario'); // BLOB en MySQL
            
            // Campo de fecha de creación con TIMESTAMP
            $table->timestamp('fec')->nullable()->default(null)->comment('fecha_creacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
