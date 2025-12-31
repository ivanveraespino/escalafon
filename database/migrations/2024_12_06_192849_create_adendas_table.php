<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdendasTable extends Migration
{
    public function up()
    {
        Schema::create('adendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vinculo')->constrained('vinculos')->onDelete('cascade'); // Relación con vinculos
            $table->date('fecha_fin')->nullable();
            $table->integer('idtd');
            $table->string('nrodoc');
            $table->integer('archivo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adendas');
    }
}
