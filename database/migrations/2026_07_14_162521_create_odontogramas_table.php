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
        Schema::create('odontogramas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained('consultas')->cascadeOnDelete();

            // Aquí guardaremos un JSON con el estado de cada diente
            // Ejemplo: {"11": 1, "12": 2, "13": 5} donde los números corresponden al catálogo (1=Sano, 2=Cariado...)[cite: 1]
            $table->json('estado_dientes')->nullable(); //[cite: 1]
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odontogramas');
    }
};
