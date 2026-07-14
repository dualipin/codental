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
        Schema::create('credenciales', function (Blueprint $table) {
            //'idc' como string y clave primaria (sin autoincremento)
            $table->string('idc', 40)->primary()->comment('idcredencial');
            
            // Campo que conectará con la tabla usuarios mediante una llave foránea (foreign key)
            $table->string('user', 40)->comment('idusuario');
            
            // Otros campos requeridos y opcionales
            $table->string('name', 30)->comment('username');
            $table->string('pass', 256)->comment('contraseña');
            $table->string('tok', 10)->nullable()->default(null)->comment('token_recuperacion');
            $table->timestamp('tim')->nullable()->default(null)->comment('ultimo_login');

            // Definición de la Llave Foránea (Foreign Key)
            $table->foreign('user')
                  ->references('user')
                  ->on('usuarios')
                  ->onUpdate('no action')
                  ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credenciales');
    }
};
