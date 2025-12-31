<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArchivoTable extends Migration
{
    public function up()
    {
        Schema::table('archivo', function (Blueprint $table) {
            // Change the file column type to varbinary(max)
            $table->binary('file')->change();
        });
    }

    public function down()
    {
        Schema::table('archivo', function (Blueprint $table) {
            // Revert the changes made in the up() method
            $table->string('file')->change();
        });
    }
}
