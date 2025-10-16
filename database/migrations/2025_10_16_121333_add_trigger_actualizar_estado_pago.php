<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear función que actualiza el estado del pago
        DB::unprepared("
            CREATE OR REPLACE FUNCTION actualizar_estado_pago()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Si el monto pendiente es 0 o menor, está pagado
                IF NEW.monto_pendiente <= 0 THEN
                    NEW.estado = 'pagado';
                -- Si el monto pendiente es menor al total, está parcial
                ELSIF NEW.monto_pendiente < NEW.monto_total THEN
                    NEW.estado = 'parcial';
                -- Si no, está pendiente
                ELSE
                    NEW.estado = 'pendiente';
                END IF;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Crear trigger que ejecuta la función antes de INSERT o UPDATE
        DB::unprepared("
            CREATE TRIGGER trg_actualizar_estado_pago
            BEFORE INSERT OR UPDATE ON pago
            FOR EACH ROW
            EXECUTE FUNCTION actualizar_estado_pago();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar el trigger y la función si se revierte la migración
        DB::unprepared("DROP TRIGGER IF EXISTS trg_actualizar_estado_pago ON pago;");
        DB::unprepared("DROP FUNCTION IF EXISTS actualizar_estado_pago();");
    }
};