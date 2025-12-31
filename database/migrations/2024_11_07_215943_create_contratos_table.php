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
        Schema::create('vinculos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('fk_contratos_personal');
            $table->text('id_unidad_organica')->nullable();
            $table->text('id_unidad_organica')->nullable();
            $table->text('obras_pro')->nullable();
            $table->text('descripcion_cese')->nullable();
            $table->integer('id_regimen')->nullable();
            $table->integer('archivo')->nullable();
            $table->integer('archivo_cese')->nullable();
            $table->integer('id_condicion_laboral')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('id_tipo_documento')->nullable();
            $table->integer('id_tipo_documento_fin')->nullable();
            $table->string('nro_doc', 50)->nullable();
            $table->string('nro_doc_fin', 50)->nullable();
            $table->integer('id_accion_vin')->nullable();
            $table->date('fecha_doc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculos');
    }
};
