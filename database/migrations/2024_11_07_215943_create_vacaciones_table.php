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
        Schema::create('vacaciones', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('personal_id');
            $table->integer('idtd')->nullable();
            $table->string('nrodoc', 50)->nullable();
            $table->string('periodo', 50)->nullable();
            $table->string('mes', 50)->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('obs')->nullable();
            $table->integer('dias')->nullable();
            $table->date('fechadoc')->nullable();
            $table->string('archivo', 100)->nullable();
            $table->string('suspencion', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacaciones');
    }
};
