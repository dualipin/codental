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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->cascadeOnDelete();
            $table->date('fecha_consulta'); //[cite: 1]

            // Habitus exterior / Signos vitales[cite: 1]
            $table->string('peso')->nullable(); //[cite: 1]
            $table->string('estatura')->nullable(); //[cite: 1]
            $table->string('temperatura')->nullable(); //[cite: 1]
            $table->string('frecuencia_cardiaca')->nullable(); //[cite: 1]
            $table->string('frecuencia_respiratoria')->nullable(); //[cite: 1]
            $table->string('presion_arterial')->nullable(); //[cite: 1]

            // Evolución[cite: 1]
            $table->text('nota_evolucion')->nullable(); //[cite: 1]

            // Firmas (pueden ser booleanos de confirmación o rutas a una imagen de la firma)
            $table->boolean('firma_paciente')->default(false); //[cite: 1]
            $table->boolean('firma_doctor')->default(false); //[cite: 1]

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
