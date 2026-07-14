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
        Schema::create('pac_trat_sel', function (Blueprint $table) {
            $table->id('id_pts');            // ID paciente tratamiento seleccionado
            $table->string('id_pac', 40); // ID paciente
            $table->foreign('id_pac')->references('idp')->on('pacientes')->cascadeOnDelete();
            $table->foreignId('id_edt')->constrained('enf_die_trat', 'id_edt')->onDelete('cascade'); // ID enfermedad diente tratamiento
            $table->foreignId('id_trat')->constrained('cat_tratamientos', 'id_trat')->onDelete('cascade'); // ID tratamiento
            $table->foreignId('id_prec')->constrained('precios_trat', 'id_precio')->onDelete('cascade'); // ID precio
            
            // Campos de descuento (NUEVOS)
            $table->decimal('des', 5, 2)->default(0.00)->comment('Porcentaje de descuento'); // descuento
            $table->enum('tip_des', ['Porcentaje', 'Monto fijo'])->default('Porcentaje')->comment('Tipo de descuento'); // tipo descuento
            $table->decimal('mon_des', 10, 2)->default(0.00)->comment('Monto descontado'); // monto descontado
            $table->decimal('mon_fin', 10, 2)->default(0.00)->comment('Monto final con descuento'); // monto final
            $table->string('mot_des', 100)->nullable()->comment('Motivo del descuento'); // motivo descuento
            
            $table->date('fec_sel');          // fecha_seleccion
            $table->enum('est', ['Seleccionado', 'Aprobado', 'En progreso', 'Completado', 'Cancelado'])->default('Seleccionado'); // estado
            $table->date('fec_apr')->nullable(); // fecha_aprobacion
            $table->date('fec_ini')->nullable(); // fecha_inicio
            $table->date('fec_com')->nullable(); // fecha_completado
            $table->decimal('mon', 10, 2);    // monto_tratamiento
            $table->string('obs', 150)->nullable(); // observaciones
            $table->timestamps();
            
            $table->index(['id_pac', 'est']);
            $table->index(['est', 'fec_sel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pac_trat_sel');
    }
};
