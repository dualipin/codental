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
        Schema::create('caras_dentales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');       // "Oclusal", "Mesial", etc.
            $table->string('codigo', 2)->nullable()->index();  // V, L, M, D, O, C - útil para notación rápida en el odontograma
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caras_dentales');
    }
};
