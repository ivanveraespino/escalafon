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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('personal_id');
            $table->integer('tipo_movimiento')->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->string('oficina_o', 50)->nullable();
            $table->string('oficina_d', 50)->nullable();
            $table->string('cargo', 100)->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->date('fechadoc')->nullable();
            $table->integer('idtd')->nullable();
            $table->string('nrodoc', 50)->nullable();
            $table->integer('archivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
