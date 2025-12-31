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
        Schema::table('nombre_tabla', function (Blueprint $table) {
            $table->string('nombre_columna', 255)->nullable()->change(); // Ejemplo: cambiar tamaño y permitir valores nulos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nombre_tabla', function (Blueprint $table) {
            $table->string('nombre_columna', 100)->change(); // Revertir cambios si es necesario
        });
    }
};
