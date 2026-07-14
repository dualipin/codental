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
        Schema::create('historias_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->cascadeOnDelete();

            $table->text('motivo_consulta')->nullable(); //[cite: 1]
            $table->text('antecedentes_hereditarios')->nullable(); //[cite: 1]

            // Antecedentes Patológicos (Integrando lo que vimos antes)
            $table->text('alergias')->nullable(); //[cite: 1]
            $table->text('medicacion_actual')->nullable(); //[cite: 1]
            $table->string('nombre_medico')->nullable(); //[cite: 1]
            $table->string('telefono_medico')->nullable(); //[cite: 1]
            $table->json('enfermedades_previas')->nullable(); // Para los checkboxes[cite: 1]
            $table->string('otras_enfermedades_especifique')->nullable(); //[cite: 1]

            // Antecedentes No Patológicos (Agrupados en JSON para ahorrar columnas)
            $table->json('habitos_toxicos')->nullable(); // Tabaco, Alcohol, Drogas[cite: 1]
            $table->string('grupo_sanguineo')->nullable(); //[cite: 1]
            $table->json('ginecoobstetricos')->nullable(); // Embarazo, meses de bebe, lactancia[cite: 1]
            $table->json('estilo_vida')->nullable(); // Deporte, Alimentación, Higiene[cite: 1]
            $table->text('cirugias_hospitalizaciones')->nullable(); //[cite: 1]

            // Exploración y Aparatos
            $table->text('padecimiento_actual')->nullable(); //[cite: 1]
            $table->text('interrogatorio_sistemas')->nullable(); //[cite: 1]
            $table->text('examenes_laboratorio')->nullable(); //[cite: 1]

            // Bucodentales y ATM
            $table->json('antecedentes_bucodentales')->nullable(
            ); // Ultima visita, cepillado, dolor, sangrado, habitos[cite: 1]
            $table->json('atm')->nullable(); // Clase Molar, Canina, Overjet, Overbite, chasquidos[cite: 1]
            $table->text('tejidos_blandos_duros')->nullable(); //[cite: 1]

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_clinicas');
    }
};
