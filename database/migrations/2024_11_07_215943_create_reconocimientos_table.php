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
        Schema::create('reconocimientos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('personal_id')->nullable()->index('personal_id');
            $table->integer('idtd')->nullable();
            $table->string('nrodoc', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fechadoc')->nullable();
            $table->string('archivo', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reconocimientos');
    }
};
