<?php

namespace App\Providers;

use App\Models\BackpackUser;
use App\Models\Catmatseratualizacao;
use App\Models\Comunica;
use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratodespesaacessoria;
use App\Models\Contratohistorico;
use App\Models\Contratoitem;
use App\Models\Contratosfpadrao;
use App\Models\ContratoPublicacoes;
use App\Models\Saldohistoricoitem;
use App\Models\SfOrcEmpenhoDados;
use App\Models\Siasgcompra;
use App\Models\Siasgcontrato;
use App\Models\Subrogacao;
use App\Models\Movimentacaocontratoconta;
use App\Models\Lancamento;

use App\Observers\CatmatseratualizacaoObserver;
use App\Observers\ComunicaObserver;
use App\Observers\ContratocronogramaObserve;
use App\Observers\ContratodespesaacessoriaObserver;
use App\Observers\ContratohistoricoObserve;
use App\Observers\ContratopublicacaoObserver;
use App\Observers\ContratoitemObserver;
use App\Observers\ContratoObserve;
use App\Observers\ContratosfpadraoObserver;
use App\Observers\SaldohistoricoitemObserver;
use App\Observers\SforcempenhodadosObserver;
use App\Observers\SiasgcompraObserver;
use App\Observers\SiasgcontratoObserver;
use App\Observers\SubrogacaoObserver;
use App\Observers\UsuarioObserver;
use App\Observers\MovimentacaocontratocontaObserver;
use App\Observers\LancamentoObserver;

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

        BackpackUser::observe(UsuarioObserver::class);
        Contrato::observe(ContratoObserve::class);
        Contratohistorico::observe(ContratohistoricoObserve::class);
        Contratocronograma::observe(ContratocronogramaObserve::class);
        ContratoPublicacoes::observe(ContratopublicacaoObserver::class);
        Contratoitem::observe(ContratoitemObserver::class);
        Saldohistoricoitem::observe(SaldohistoricoitemObserver::class);
        Catmatseratualizacao::observe(CatmatseratualizacaoObserver::class);
        Comunica::observe(ComunicaObserver::class);
        Contratosfpadrao::observe(ContratosfpadraoObserver::class);
        Subrogacao::observe(SubrogacaoObserver::class);
        Contratodespesaacessoria::observe(ContratodespesaacessoriaObserver::class);
        Siasgcompra::observe(SiasgcompraObserver::class);
        Siasgcontrato::observe(SiasgcontratoObserver::class);
        SfOrcEmpenhoDados::observe(SforcempenhodadosObserver::class);
        Movimentacaocontratoconta::observe(MovimentacaocontratocontaObserver::class);
        Lancamento::observe(LancamentoObserver::class);
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
