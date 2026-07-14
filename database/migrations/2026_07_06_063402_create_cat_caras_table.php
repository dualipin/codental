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
        Schema::create('cat_caras', function (Blueprint $table) {
            $table->id('id_cara')->comment('ID cara'); //ID
            $table->string('nom', 20)->comment('nombre');
            $table->string('abr', 5)->comment('abreviatura');
            $table->string('desc', 50)->nullable()->comment('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_caras');
    }
};
