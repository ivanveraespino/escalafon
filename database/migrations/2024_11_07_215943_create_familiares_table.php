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
        Schema::create('familiares', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('personal_id');
            $table->string('apaterno', 50)->nullable();
            $table->string('amaterno', 50)->nullable();
            $table->string('nombres', 50)->nullable();
            $table->string('parentesco', 50)->nullable();
            $table->date('fechanacimiento')->nullable();
            $table->string('lugarlaboral', 50)->nullable();
            $table->string('ocupacion', 50)->nullable();
            $table->string('estadocivil', 50)->nullable();
            $table->string('emergencia', 50)->nullable();
            $table->string('direccion', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('vive', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('familiares');
    }
};
