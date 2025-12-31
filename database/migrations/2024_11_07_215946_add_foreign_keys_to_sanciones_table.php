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
        Schema::table('sanciones', function (Blueprint $table) {
            $table->foreign(['personal_id'], 'FK_sanciones_personal')->references(['dni'])->on('personal')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanciones', function (Blueprint $table) {
            $table->dropForeign('FK_sanciones_personal');
        });
    }
};
