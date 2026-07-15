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
        Schema::create('dientes', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_fdi');
            $table->string('nombre');
            $table->char('cuadrante', 1);
            $table->string('tipo');
            $table->string('posicion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dientes');
    }
};
