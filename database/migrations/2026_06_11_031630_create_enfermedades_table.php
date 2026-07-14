<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enfermedades', function (Blueprint $table) {
            $table->increments('idae')->comment('id_enfermedad');
            $table->string('cod_enf', 10)->unique()->comment('Codigo del checkbox e1-e23');
            $table->string('enf', 50)->comment('nombre enfermedad');
            $table->timestamps();
        });

        // 2. Insertamos exactamente tus 23 enfermedades basadas en tus labels
        DB::table('enfermedades')->insert([
            ['cod_enf' => 'e1', 'enf' => 'Diabetes', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e2', 'enf' => 'VIH', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e3', 'enf' => 'Asma', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e4', 'enf' => 'Hipertension', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e5', 'enf' => 'Sida', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e6', 'enf' => 'Infartos', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e7', 'enf' => 'Cáncer', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e8', 'enf' => 'VPH', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e9', 'enf' => 'Epilepsia', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e10', 'enf' => 'Enfermedades mentales', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e11', 'enf' => 'Enfermedades cardiacas', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e12', 'enf' => 'Hepatitis', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e13', 'enf' => 'Enfermedades hepáticas', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e14', 'enf' => 'Enfermedades glandulares', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e15', 'enf' => 'Anemia', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e16', 'enf' => 'Enfermedades metabólicas', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e17', 'enf' => 'Enfermedades respiratorias', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e18', 'enf' => 'Tuberculosis', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e19', 'enf' => 'Enfermedades de transmisión sexual', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e20', 'enf' => 'Enfermedades digestivas', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e21', 'enf' => 'Otras enfermedades', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e22', 'enf' => 'Enfermedades urinarias', 'created_at' => now(), 'updated_at' => now()],
            ['cod_enf' => 'e23', 'enf' => 'Enfermedades óseas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfermedades');
    }
};
