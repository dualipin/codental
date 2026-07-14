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
        Schema::create('odonto_inicial', function (Blueprint $table) {
            $table->id('id_odoi')->comment("ID_odontograma inicial");           // ID odontograma inicial
            $table->string('id_pac', 40)->comment("ID paciente"); // ID paciente
            $table->foreign('id_pac')->references('idp')->on('pacientes')->cascadeOnDelete();
            $table->date('fec_reg')->comment("Fecha registro");          // fecha_registro
            $table->text('obs')->comment("Observaciones")->nullable();  // observaciones
            $table->timestamps();
            
            $table->index('id_pac');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odonto_inicial');
    }
};
