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
        Schema::create('domicilio', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->unique('personal_id');
            $table->string('dactual', 50)->nullable();
            $table->string('tipodom', 50)->nullable();
            $table->integer('iddep')->nullable();
            $table->integer('idpro')->nullable();
            $table->integer('iddis')->nullable();
            $table->string('numero', 50)->nullable();
            $table->string('interior', 50)->nullable();
            $table->string('referencia', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicilio');
    }
};
