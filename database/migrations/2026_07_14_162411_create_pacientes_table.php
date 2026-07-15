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
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('telefono')->unique();
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['masculino', 'femenino', 'otro']);
            $table->string('direccion')->nullable();
            $table->string('estado')->nullable();
            $table->string('municipio')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('religion')->nullable();
            $table->boolean('verificado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
