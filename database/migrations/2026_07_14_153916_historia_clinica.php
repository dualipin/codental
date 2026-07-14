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
        // ficha de identificación
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

        // antecedentes hereditarios familiares
        Schema::create('antecedentes_familiares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->text('antecedente');
            $table->timestamps();
        });

        // antecedentes personales patológicos
        Schema::create('antecedentes_patologicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->text('alergias');
            $table->text('medicacion_actual')->nullable();
            $table->string('nombre_medico')->nullable();
            $table->string('telefono_medico', 25)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
