<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrabajosTable extends Migration
{
    public function up()
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->text('archivoescritura')->nullable();
            $table->text('archivoesquema')->nullable();
            $table->text('puntosrecorrido')->nullable();
            $table->text('archivodwg')->nullable();
            $table->text('archivokml')->nullable();
            $table->text('notas')->nullable();
            $table->text('insumos')->nullable();
            $table->foreignId('cliente')->constrained('cliente')->onDelete('cascade');
            $table->foreignId('responsable')->nullable()->constrained('responsable')->onDelete('set null');
            $table->date('fechatrabajo')->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('empleado', 20)->nullable();
            $table->decimal('montototal', 12, 2)->nullable();
            $table->decimal('montopagado', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('empleado')->references('dui')->on('empleados')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trabajos');
    }
}
