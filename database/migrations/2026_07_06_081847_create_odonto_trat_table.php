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
        Schema::create('odonto_trat', function (Blueprint $table) {
            $table->id('id_odot')->comment("ID_odontograma_tratamiento");           // ID odontograma tratamiento
            $table->string('id_pac', 40)->comment("ID paciente"); // ID paciente
            $table->foreign('id_pac')->references('idp')->on('pacientes')->cascadeOnDelete();
            $table->foreignId('id_odoi')->comment("ID odontograma inicial")->constrained('odonto_inicial', 'id_odoi')->onDelete('cascade'); // ID odontograma inicial
            $table->date('fec_ult')->comment("Fecha última actualización");          // fecha_ultima_actualizacion
            $table->integer('ver')->comment("Versión")->default(1); // version
            $table->boolean('act')->comment("Activo")->default(true); // activo
            $table->text('obs')->comment("Observaciones")->nullable();  // observaciones
            $table->timestamps();
            
            $table->index(['id_pac', 'act']);
            $table->index('act');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odonto_trat');
    }
};
