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
        Schema::table('empleados', function (Blueprint $table) {
            $table->unsignedBigInteger('cargo_id')->nullable()->after('cargo');
        });

        // Insertar cargos únicos desde empleados.cargo
        DB::statement("
            INSERT INTO cargos (nombre, created_at, updated_at)
            SELECT DISTINCT cargo, NOW(), NOW()
            FROM empleados
            WHERE cargo IS NOT NULL AND cargo <> ''
        ");

        // Actualizar empleados con el cargo_id correspondiente
        DB::statement("
            UPDATE empleados e
            SET cargo_id = c.id
            FROM cargos c
            WHERE e.cargo = c.nombre
        ");

        // Crear la foreign key
        Schema::table('empleados', function (Blueprint $table) {
            $table->foreign('cargo_id')
                  ->references('id')->on('cargos')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['cargo_id']);
            $table->dropColumn('cargo_id');
        });
    }
};
