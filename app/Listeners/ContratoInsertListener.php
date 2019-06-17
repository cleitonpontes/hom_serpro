<?php

namespace App\Listeners;

use App\Events\ContratoInsertEvent;
use App\Events\ContratohistoricoEvent;
use App\Models\Contratohistorico;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContratoInsertListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ContratoInsertEvent  $event->contrato
     * @return void
     */
    public function handle(ContratoInsertEvent $event)
    {
        $instrumento = [
            'numero' => trim($event->contrato->numero),
            'contrato_id' => trim($event->contrato->id),
            'fornecedor_id' => trim($event->contrato->fornecedor_id),
            'unidade_id' => trim($event->contrato->unidade_id),
            'tipo_id' => trim($event->contrato->tipo_id),
            'categoria_id' => trim($event->contrato->categoria_id),
            'receita_despesa' => trim($event->contrato->receita_despesa),
            'processo' => trim($event->contrato->processo),
            'objeto' => trim($event->contrato->objeto),
            'info_complementar' => trim($event->contrato->info_complementar),
            'fundamento_legal' => trim($event->contrato->fundamento_legal),
            'modalidade_id' => trim($event->contrato->modalidade_id),
            'licitacao_numero' => trim($event->contrato->licitacao_numero),
            'data_assinatura' => trim($event->contrato->data_assinatura),
            'data_publicacao' => trim($event->contrato->data_publicacao),
            'vigencia_inicio' => trim($event->contrato->vigencia_inicio),
            'vigencia_fim' => trim($event->contrato->vigencia_fim),
            'valor_inicial' => trim($event->contrato->valor_inicial),
            'valor_global' => trim($event->contrato->valor_global),
            'num_parcelas' => trim($event->contrato->num_parcelas),
            'valor_parcela' => trim($event->contrato->valor_parcela),
        ];

        event(new ContratohistoricoEvent($instrumento));

    }
}
