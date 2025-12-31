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
        Schema::create('comisiones', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable();
            $table->integer('idtd')->default(0);
            $table->string('nrodoc', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->date('fechadoc')->nullable();
            $table->string('archivo', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones');
    }
};
