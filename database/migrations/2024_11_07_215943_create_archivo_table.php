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
        Schema::create('archivo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('fk_archivo_copy_personal');
            $table->string('clave', 50)->nullable()->index('fk_archivo_copy_categorias');
            $table->bigInteger('peso')->nullable();
            $table->text('nombre')->nullable();
            $table->integer('nro_folio')->nullable();
            $table->binary('file')->nullable();
            $table->string('extension', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivo');
    }
};
