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
            $table->date('fecha_consulta');

            // Habitus exterior / Signos vitales[cite: 1]
            $table->string('peso')->nullable();
            $table->string('estatura')->nullable();
            $table->string('temperatura')->nullable();
            $table->string('frecuencia_cardiaca')->nullable();
            $table->string('frecuencia_respiratoria')->nullable();
            $table->string('presion_arterial')->nullable();

            // Evolución[cite: 1]
            $table->text('nota_evolucion')->nullable();

            // Firmas (pueden ser booleanos de confirmación o rutas a una imagen de la firma)
            $table->boolean('firma_paciente')->default(false);
            $table->boolean('firma_doctor')->default(false);

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
