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
        Schema::table('estudios_especializacion', function (Blueprint $table) {
            $table->foreign(['personal_id'], 'FK_estudios_extras_personal')->references(['dni'])->on('personal')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudios_especializacion', function (Blueprint $table) {
            $table->dropForeign('FK_estudios_extras_personal');
        });
    }
};
