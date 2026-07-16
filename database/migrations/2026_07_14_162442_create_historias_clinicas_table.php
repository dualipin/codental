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

            $table->text('antecedentes_hereditarios')->nullable();

            // Antecedentes Patológicos (Integrando lo que vimos antes)
            $table->text('alergias')->nullable();
            $table->text('medicacion_actual')->nullable();
            $table->string('nombre_medico')->nullable();
            $table->string('telefono_medico')->nullable();
            $table->json('enfermedades_previas')->nullable(); // Para los checkboxes
            $table->string('otras_enfermedades')->nullable();

            // Antecedentes No Patológicos (Agrupados en JSON para ahorrar columnas)
            $table->json('habitos_toxicos')->nullable(); // Tabaco, Alcohol, Drogas
            $table->string('grupo_sanguineo')->nullable();
            $table->json('ginecoobstetricos')->nullable(); // Embarazo, meses de bebe, lactancia
            $table->json('estilo_vida')->nullable(); // Deporte, Alimentación, Higiene
            $table->text('cirugias_hospitalizaciones')->nullable();

            // Exploración y Aparatos
            $table->text('padecimiento_actual')->nullable();
            $table->text('interrogatorio_sistemas')->nullable();
            $table->text('examenes_laboratorio')->nullable();

            // Bucodentales y ATM
            $table->json('antecedentes_bucodentales')->nullable(
            ); // Ultima visita, cepillado, dolor, sangrado, habitos
            $table->json('atm')->nullable(); // Clase Molar, Canina, Overjet, Overbite, chasquidos
            $table->text('tejidos_blandos_duros')->nullable();

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
