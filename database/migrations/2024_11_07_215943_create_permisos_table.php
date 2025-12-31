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
        Schema::create('permisos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('personal_id');
            $table->integer('idtd')->nullable();
            $table->string('nrodoc', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('periodo', 50)->nullable();
            $table->string('acuentavac', 10)->nullable();
            $table->date('fechadoc')->nullable();
            $table->integer('dias')->nullable();
            $table->string('archivo', 100)->nullable();
            $table->timestamps();
            $table->string('mes', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
