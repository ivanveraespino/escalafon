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
        Schema::create('desvinculacion', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 100)->nullable();
            $table->integer('id_categoria')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desvinculacion');
    }
};
