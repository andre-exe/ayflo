<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('empleados', function (Blueprint $table) {
        $table->dropPrimary('dui'); // quitar PK de dui
        $table->bigIncrements('id'); // agregar id auto-incremental como PK
        $table->unique('dui');      // opcional
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            //
        });
    }
};
