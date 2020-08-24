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
        $historico = Contratohistorico::where('contrato_id', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura','ASC')
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
        $historico = Contratohistorico::where('contrato_id', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura','ASC')
            ->get();

        $cronograma = Contratocronograma::where('contrato_id',$contratohistorico->contrato_id)
            ->delete();

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
            ->orderBy('data_assinatura','ASC')
            ->get();

        $cronograma = Contratocronograma::where('contrato_id',$contratohistorico->contrato_id)
            ->delete();

//        $contratohistorico->cronograma()->delete();
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
            $array = $this->retornaArrayContratoHistorico($tipo,$arrayhistorico);
            dd($array);
            $contrato = new Contrato();
            $contrato->atualizaContratoFromHistorico($contrato_id, $array);

        }
    }

    public function retornaArrayContratoHistorico(Codigoitem $tipo,array $arrayhistorico)
    {

        if($tipo->descricao == 'Termo de Rescisão'){
            return $this->retornaArrayRescisao($arrayhistorico);
        }

        if($tipo->descricao == 'Termo Aditivo'){
            return $this->retornaArrayAditivo($arrayhistorico);
        }

        if($tipo->descricao == 'Termo de Apostilamento'){
            return $this->retornaArrayApostilamento($arrayhistorico);
        }
    }

    public function retornaArrayAditivo(array $arrayhistorico)
    {
        $arrayAditivo = [
            'fornecedor_id' => $arrayhistorico['fornecedor_id'],
            'unidade_id' => $arrayhistorico['unidade_id'],
            'info_complementar' => $arrayhistorico['info_complementar'],
            'vigencia_inicio' => $arrayhistorico['vigencia_inicio'],
            'vigencia_fim' => $arrayhistorico['vigencia_fim'],
            'valor_global' => $arrayhistorico['valor_global'],
            'num_parcelas' => $arrayhistorico['num_parcelas'],
            'valor_parcela' => $arrayhistorico['valor_parcela']
        ];
        (isset($arrayhistorico['situacao']))?$arrayAditivo['situacao'] = $arrayhistorico['situacao'] : "";
        return $arrayAditivo;
    }

    public function retornaArrayApostilamento(array $arrayhistorico)
    {
        $arrayApostilamento = [
            'fornecedor_id' => $arrayhistorico['fornecedor_id'],
            'unidade_id' => $arrayhistorico['unidade_id'],
            'valor_global' => $arrayhistorico['valor_global'],
            'num_parcelas' => $arrayhistorico['num_parcelas'],
            'valor_parcela' => $arrayhistorico['valor_parcela']
        ];
        (isset($arrayhistorico['situacao']))?$arrayApostilamento['situacao'] = $arrayhistorico['situacao'] : "";
        return $arrayApostilamento;
    }

    public function retornaArrayRescisao(array $arrayhistorico)
    {
        return $arrayRescisao = [
            'vigencia_fim' => $arrayhistorico['vigencia_fim'],
            'situacao' => $arrayhistorico['situacao'],
        ];
        return $arrayRescisao;
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
