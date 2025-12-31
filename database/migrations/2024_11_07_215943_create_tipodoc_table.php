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
        Schema::create('tipodoc', function (Blueprint $table) {
            $table->integer('idtd', true);
            $table->string('tipo', 50)->nullable();
            $table->text('nombre')->nullable();
            $table->text('categoria')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipodoc');
    }
};
