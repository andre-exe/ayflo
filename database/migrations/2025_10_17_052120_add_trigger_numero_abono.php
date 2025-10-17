<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear función que calcula el número de abono automáticamente
        DB::unprepared("
            CREATE OR REPLACE FUNCTION calcular_numero_abono()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Si no se especifica número de abono, calcularlo automáticamente
                IF NEW.numero_abono IS NULL THEN
                    SELECT COALESCE(MAX(numero_abono), 0) + 1 
                    INTO NEW.numero_abono
                    FROM abono
                    WHERE id_pago = NEW.id_pago;
                END IF;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Crear trigger que ejecuta la función antes de INSERT
        DB::unprepared("
            CREATE TRIGGER trg_calcular_numero_abono
            BEFORE INSERT ON abono
            FOR EACH ROW
            EXECUTE FUNCTION calcular_numero_abono();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar el trigger
        DB::unprepared("DROP TRIGGER IF EXISTS trg_calcular_numero_abono ON abono;");
        
        // Eliminar la función
        DB::unprepared("DROP FUNCTION IF EXISTS calcular_numero_abono();");
    }
};