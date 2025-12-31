<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar la tabla anterior si existe
        Schema::dropIfExists('archivo');

        // Crear la nueva tabla con la columna 'file' tipo binary
        Schema::create('archivo', function (Blueprint $table) {
            $table->id();
            $table->integer('personal_id');
            $table->string('clave')->nullable();
            $table->integer('peso')->nullable();
            $table->string('nombre')->nullable();
            $table->integer('nro_folio')->nullable();
            $table->binary('file'); // Esto es lo importante
            $table->string('extension')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la tabla 'archivo' en caso de reversión
        Schema::dropIfExists('archivo');
    }
};


