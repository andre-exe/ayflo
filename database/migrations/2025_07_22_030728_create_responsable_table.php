<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsableTable extends Migration
{
    public function up()
    {
        Schema::create('responsable', function (Blueprint $table) {
            $table->id();
            $table->string('nombresresp', 100);
            $table->string('apellidosresp', 100);
            $table->string('telefonoresp', 20)->nullable();
            $table->string('correoresp', 100)->nullable();
            $table->foreignId('cliente')->constrained('cliente')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('responsable');
    }
}
