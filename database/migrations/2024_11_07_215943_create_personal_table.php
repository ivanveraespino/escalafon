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
        Schema::create('personal', function (Blueprint $table) {
            $table->integer('dni', true);
            $table->string('nro_documento_id', 50)->nullable()->unique('nro_documento_id');
            $table->string('Apaterno', 50)->nullable();
            $table->string('Amaterno', 50)->nullable();
            $table->string('Nombres', 50)->nullable();
            $table->string('fpersonal', 100)->nullable();
            $table->date('FechaNacimiento')->nullable();
            $table->text('lprocedencia')->nullable();
            $table->string('NroColegiatura', 50)->nullable();
            $table->string('NroRuc', 50)->nullable();
            $table->string('NroEssalud', 50)->nullable();
            $table->string('CentroEssalud', 50)->nullable();
            $table->string('GrupoSanguineo', 50)->nullable();
            $table->string('NroTelefono', 50)->nullable();
            $table->string('NroCelular', 50)->nullable();
            $table->string('Correo', 50)->nullable();
            $table->string('EstadoCivil', 50)->nullable();
            $table->string('sexo', 50)->nullable();
            $table->timestamps();
            $table->string('id_tipo_personal', 50)->nullable();
            $table->string('ocupacion', 50)->nullable();
            $table->string('afiliacion_salud', 100)->nullable();
            $table->integer('archivo')->nullable();
            $table->integer('id_regimen_modalidad')->nullable();
            $table->integer('id_regimen')->nullable();
            $table->string('id_identificacion', 50)->nullable();
            $table->string('afp', 50)->nullable();
            $table->string('id_regimenp', 50)->nullable();
            $table->string('discapacidad', 50)->nullable();
            $table->string('ffaa', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
