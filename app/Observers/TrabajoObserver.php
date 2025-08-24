<?php

namespace App\Observers;

use App\Models\Trabajo;
use App\Models\Bitacora;

class TrabajoObserver
{
    /**
     * Handle the Trabajo "created" event.
     */
    public function created(Trabajo $trabajo): void
    {
       
        Bitacora::create([
            'cliente' => $trabajo->cliente,           // esta acceiendo al cliente desde trabajo
            'responsable' => $trabajo->responsable,   // esta acceiendo al responsable desde trabajo
            'monto' => $trabajo->montototal,
            'idtrabajo' => $trabajo->id,
            'fechatrabajobitacora' => $trabajo->fechatrabajo, // esta accediendo a la fecha del trabajo
            'descripcionbitacora' => $trabajo->descripcion
        ]);
    }

        public function updated(Trabajo $trabajo): void
    {
        //
    }

    /**
     * Handle the Trabajo "deleted" event.
     */
    public function deleted(Trabajo $trabajo): void
    {
        //
    }

    /**
     * Handle the Trabajo "restored" event.
     */
    public function restored(Trabajo $trabajo): void
    {
        //
    }

    /**
     * Handle the Trabajo "force deleted" event.
     */
    public function forceDeleted(Trabajo $trabajo): void
    {
        //
    }

}
