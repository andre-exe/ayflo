<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->string('dui', 20)->primary();
            $table->string('nombresemp', 100);
            $table->string('apellidosemp', 100);
            $table->string('telefonemp', 20)->nullable();
            $table->string('direccionemp', 255)->nullable();
            $table->string('cargo', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
