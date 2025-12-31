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
        Schema::create('cronograma_vac', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('personal_id');
            $table->integer('idtd')->nullable();
            $table->string('nrodoc', 50)->nullable();
            $table->integer('periodo')->nullable();
            $table->string('mes', 50)->nullable();
            $table->text('fecha_ini')->nullable();
            $table->text('fecha_fin')->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('dias', 50)->nullable();
            $table->integer('idvo')->nullable()->index('idvo');
            $table->string('idvr', 50)->nullable()->index('idvr');
            $table->date('fechadoc')->nullable();
            $table->string('archivo', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->string('id_subida', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronograma_vac');
    }
};
