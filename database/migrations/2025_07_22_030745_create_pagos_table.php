<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('cliente')->onDelete('cascade');
            $table->foreignId('id_responsable')->nullable()->constrained('responsable')->onDelete('set null');
            $table->decimal('montototal', 12, 2);
            $table->decimal('abono', 12, 2);
            $table->date('fechaabono');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
