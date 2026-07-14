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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); //
            $table->date('fecha_nacimiento')->nullable(); //[cite: 1]
            $table->integer('edad')->nullable(); //[cite: 1]
            $table->string('sexo', 20)->nullable(); //[cite: 1]
            $table->string('direccion')->nullable(); //[cite: 1]
            $table->string('estado')->nullable(); //[cite: 1]
            $table->string('municipio')->nullable(); //[cite: 1]
            $table->string('telefono')->nullable(); //[cite: 1]
            $table->string('ocupacion')->nullable(); //[cite: 1]
            $table->string('estado_civil')->nullable(); //[cite: 1]
            $table->string('correo_electronico')->nullable(); //[cite: 1]
            $table->string('religion')->nullable(); //[cite: 1]
            $table->string('enviado_por')->nullable(); //[cite: 1]
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
