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
        Schema::create('cat_dientes', function (Blueprint $table) {
            $table->id('id_diente')->comment('ID diente');         // ID diente
            $table->integer('num_fdi')->unique()->comment('numero FDI'); // numero FDI
            $table->string('nom', 30)->comment('nombre');        // nombre
            $table->char('cuad', 1)->comment('cuadrante');          // cuadrante
            $table->string('tipo', 20)->comment('tipo');       // tipo
            $table->string('pos', 10)->comment('posicion');        // posicion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_dientes');
    }
};
