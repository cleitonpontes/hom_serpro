<?php

namespace App\Providers;

use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use App\Observers\ContratocronogramaObserve;
use App\Observers\ContratohistoricoObserve;
use App\Observers\ContratoObserve;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if (!app()->runningInConsole()) {
            Activity::saving(function (Activity $activity) {
                $activity->ip = \Request::ip();
                if (backpack_user()) {
                    $activity->causer_id = backpack_user()->id;
                }
            });
        }

        Contrato::observe(ContratoObserve::class);
        Contratohistorico::observe(ContratohistoricoObserve::class);
        Contratocronograma::observe(ContratocronogramaObserve::class);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
