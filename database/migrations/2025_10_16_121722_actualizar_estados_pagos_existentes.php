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
        // Actualizar el estado de todos los pagos existentes
        DB::statement("
            UPDATE pago 
            SET estado = CASE 
                WHEN monto_pendiente <= 0 THEN 'pagado'
                WHEN monto_pendiente < monto_total THEN 'parcial'
                ELSE 'pendiente'
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario revertir nada
        // Los estados se quedarán como están
    }
};