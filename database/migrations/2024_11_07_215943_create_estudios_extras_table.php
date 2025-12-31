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
        Schema::create('estudios_especializacion', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('personal_id');
            $table->string('nombre', 50)->nullable();
            $table->string('centroestudios', 50)->nullable();
            $table->integer('horas')->nullable();
            $table->string('archivo', 100)->nullable();
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
        Schema::dropIfExists('estudios_especializacion');
    }
};
