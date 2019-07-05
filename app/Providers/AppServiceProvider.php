<?php

namespace App\Providers;

use App\Models\Catmatseratualizacao;
use App\Models\Comunica;
use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use App\Models\Contratoitem;
use App\Models\SfPadrao;
use App\Observers\CatmatseratualizacaoObserver;
use App\Observers\ComunicaObserver;
use App\Observers\ContratocronogramaObserve;
use App\Observers\ContratohistoricoObserve;
use App\Observers\ContratoitemObserver;
use App\Observers\ContratoObserve;
use App\Observers\SfpadraoObserver;
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
        Contratoitem::observe(ContratoitemObserver::class);
        Catmatseratualizacao::observe(CatmatseratualizacaoObserver::class);
        Comunica::observe(ComunicaObserver::class);
        SfPadrao::observe(SfpadraoObserver::class);

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
