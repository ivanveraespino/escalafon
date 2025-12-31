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
        Schema::create('compensaciones', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('fk_compensaciones_personal');
            $table->string('tipo_compensacion', 50)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->integer('id_tipo_documento')->nullable();
            $table->string('nro_documento', 50)->nullable();
            $table->date('fecha_documento')->nullable();
            $table->integer('archivo')->nullable();
            $table->timestamps();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensaciones');
    }
};
