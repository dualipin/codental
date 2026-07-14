<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            // Datos del Paciente
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->date('fecha_nacimiento');
            $table->string('sexo', 1);
            $table->string('direccion');
            $table->string('estado', 100);
            $table->string('municipio', 100);
            $table->string('telefono', 10)->unique();
            $table->string('ocupacion');
            $table->string('estado_civil', 100);
            $table->string('correo')->nullable();
            $table->string('religion', 100)->nullable();
            $table->timestamps();
        });


        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            // Datos del Paciente
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();

            // NUEVO CONTROL: Fechas y Horas de Inicio y Fin combinadas
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');

            $table->string('estatus', 15)->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
        Schema::dropIfExists('pacientes');
    }
};
