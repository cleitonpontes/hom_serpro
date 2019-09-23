<?php

namespace App\Observers;

use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use Illuminate\Support\Facades\DB;

class ContratohistoricoObserve
{

    public function __construct(Contratocronograma $contratocronograma)
    {
        $this->contratocronograma = $contratocronograma;
    }

    /**
     * Handle the contratohistorico "created" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function created(Contratohistorico $contratohistorico)
    {
        $historico = Contratohistorico::where('contrato_id', '=', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        $this->contratocronograma->inserirCronogramaFromHistorico($contratohistorico);
        $this->atualizaContrato($historico);
        $this->createEventCalendar($contratohistorico);

    }

    /**
     * Handle the contratohistorico "updated" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function updated(Contratohistorico $contratohistorico)
    {
        $historico = Contratohistorico::where('contrato_id', '=', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        $this->contratocronograma->atualizaCronogramaFromHistorico($historico);
        $this->atualizaContrato($historico);
        $this->createEventCalendar($contratohistorico);

    }

    /**
     * Handle the contratohistorico "deleted" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function deleted(Contratohistorico $contratohistorico)
    {

        $historico = Contratohistorico::where('contrato_id', '=', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        $contratohistorico->cronograma()->delete();
        $this->contratocronograma->atualizaCronogramaFromHistorico($historico);
        $this->atualizaContrato($historico);
    }

    /**
     * Handle the contratohistorico "restored" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function restored(Contratohistorico $contratohistorico)
    {
        //
    }

    /**
     * Handle the contratohistorico "force deleted" event.
     *
     * @param  \App\Models\Contratohistorico $contratohistorico
     * @return void
     */
    public function forceDeleted(Contratohistorico $contratohistorico)
    {
        //
    }

    private function atualizaContrato($contratohistorico)
    {
        foreach ($contratohistorico as $h) {

            $contrato_id = $h->contrato_id;
            $arrayhistorico = $h->toArray();

            $tipo = Codigoitem::find($arrayhistorico['tipo_id']);

            if ($tipo->descricao == 'Termo Aditivo' or $tipo->descricao == 'Termo de Apostilamento') {
                unset($arrayhistorico['numero']);
                unset($arrayhistorico['receita_despesa']);
                unset($arrayhistorico['tipo_id']);
                unset($arrayhistorico['categoria_id']);
                unset($arrayhistorico['processo']);
                unset($arrayhistorico['modalidade_id']);
                unset($arrayhistorico['licitacao_numero']);
                unset($arrayhistorico['data_assinatura']);
                unset($arrayhistorico['data_publicacao']);
                unset($arrayhistorico['valor_inicial']);
                unset($arrayhistorico['novo_valor_global']);
                unset($arrayhistorico['novo_num_parcelas']);
                unset($arrayhistorico['novo_valor_parcela']);
                unset($arrayhistorico['data_inicio_novo_valor']);
                unset($arrayhistorico['unidades_requisitantes']);

            }
            unset($arrayhistorico['id']);
            unset($arrayhistorico['contrato_id']);
            unset($arrayhistorico['observacao']);
            unset($arrayhistorico['created_at']);
            unset($arrayhistorico['updated_at']);
            unset($arrayhistorico['retroativo']);
            unset($arrayhistorico['retroativo_mesref_de']);
            unset($arrayhistorico['retroativo_anoref_de']);
            unset($arrayhistorico['retroativo_mesref_ate']);
            unset($arrayhistorico['retroativo_anoref_ate']);
            unset($arrayhistorico['retroativo_vencimento']);
            unset($arrayhistorico['retroativo_valor']);

            $array = array_filter($arrayhistorico, function ($a) {
                return trim($a) !== "";
            });

            $contrato = new Contrato();
            $contrato->atualizaContratoFromHistorico($contrato_id, $array);



        }
    }

    public function createEventCalendar(Contratohistorico $contratohistorico)
    {
        $contrato = Contrato::find($contratohistorico->contrato_id);

        $fornecedor = $contrato->fornecedor->cpf_cnpj_idgener . ' - ' . $contrato->fornecedor->nome;
        $ug = $contrato->unidade->codigo . ' - ' . $contrato->unidade->nomeresumido;

        $tituloinicio = 'Início Vigência Contrato: ' . $contrato->numero. ' Fornecedor: ' . $fornecedor . ' da UG: ' . $ug;
        $titulofim = 'Fim Vigência Contrato: ' . $contrato->numero. ' Fornecedor: ' . $fornecedor . ' da UG: ' . $ug;

        $events= [
            [
                'title' => $tituloinicio,
                'start_date' => new \DateTime($contrato->vigencia_inicio),
                'end_date' => new \DateTime($contrato->vigencia_inicio),
                'unidade_id' => $contrato->unidade_id
            ],
            [
                'title' => $titulofim,
                'start_date' => new \DateTime($contrato->vigencia_fim),
                'end_date' => new \DateTime($contrato->vigencia_fim),
                'unidade_id' => $contrato->unidade_id
            ]

        ];

        foreach ($events as $e){
            $calendario = new CalendarEvent();
            $calendario->insertEvents($e);
        }

        return $calendario;

    }

}
