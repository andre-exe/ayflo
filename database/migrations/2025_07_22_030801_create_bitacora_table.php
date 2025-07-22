<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBitacoraTable extends Migration
{
    public function up()
    {
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente')->constrained('cliente')->onDelete('cascade');
            $table->foreignId('responsable')->nullable()->constrained('responsable')->onDelete('set null');
            $table->decimal('monto', 12, 2);
            $table->foreignId('idtrabajo')->constrained('trabajos')->onDelete('cascade');
            $table->date('fechatrabajobitacora');
            $table->text('descripcionbitacora')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bitacora');
    }
}
