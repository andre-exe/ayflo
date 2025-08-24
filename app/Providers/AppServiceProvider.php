<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Trabajo;
use App\Observers\TrabajoObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
       public function boot(): void
{
    Trabajo::observe(TrabajoObserver::class); //esto es para que cada vez que se ingrese un nuevo tranbajo se me llene bitacora

}
}