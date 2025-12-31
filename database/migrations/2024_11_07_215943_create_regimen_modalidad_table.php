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
        Schema::create('condicion_laboral', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('nombre')->nullable();
            $table->string('abreviatura', 50)->nullable();
            $table->text('descripcion_regimen')->nullable();
            $table->timestamps();
            $table->string('tipo_personal', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condicion_laboral');
    }
};
