<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoCompensacionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_compensacion', function (Blueprint $table) {
            $table->id(); // Campo 'id'
            $table->string('nombre'); // Campo 'nombre'
            $table->timestamps(); // Campos 'created_at' y 'updated_at' (opcional)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_compensacion');
    }
}
