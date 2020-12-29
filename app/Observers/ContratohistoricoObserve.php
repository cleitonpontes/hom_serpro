<?php

namespace App\Observers;

use App\Http\Controllers\Publicacao\DiarioOficialClass;
use App\Http\Traits\BuscaCodigoItens;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use App\Models\ContratoHistoricoMinutaEmpenho;
use App\Models\ContratoPublicacoes;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ContratohistoricoObserve
{

    use BuscaCodigoItens;

    public function __construct(Contratocronograma $contratocronograma)
    {
        $this->contratocronograma = $contratocronograma;
    }

    /**
     * Handle the contratohistorico "created" event.
     *
     * @param Contratohistorico $contratohistorico
     * @return void
     */
    public function created(Contratohistorico $contratohistorico)
    {
        $sisg = (isset($contratohistorico->unidade->sisg)) ? $contratohistorico->unidade->sisg : '';

        $historico = Contratohistorico::where('contrato_id', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura', 'ASC')
            ->get();

        $this->contratocronograma->inserirCronogramaFromHistorico($contratohistorico);
        $this->atualizaContrato($historico);
        $this->createEventCalendar($contratohistorico);

       // $situacao = $this->setSituacao($sisg, $contratohistorico->data_publicacao);

        $tipoEmpenho = $this->retornaIdCodigoItem('Tipo de Contrato', 'Empenho');
        $tipoOutros = $this->retornaIdCodigoItem('Tipo de Contrato', 'Outros');
        if ($contratohistorico->tipo_id != $tipoEmpenho && $contratohistorico->tipo_id != $tipoOutros) {
//            ContratoPublicacoes::create([
//                'contratohistorico_id' => $contratohistorico->id,
//                'data_publicacao' => $contratohistorico->data_publicacao,
//                'status' => 'Pendente',
//                'status_publicacao_id' => $situacao->id,
//                'texto_dou' => '',//@DiarioOficialClass::retornaTextoModelo($contratohistorico),
//                'tipo_pagamento_id' => $this->retornaIdCodigoItem('Forma Pagamento', 'Isento'),
//                'motivo_isencao' =>
//                    ($sisg)
//                        ? $this->retornaIdCodigoItem(
//                            'Motivo Isenção',
//                            'Atos oficiais administrativos, normativos e de pessoal dos ministérios e órgãos subordinados'
//                        )
//                        : ''
//            ]);
        }
    }

    /**
     * Handle the contratohistorico "updated" event.
     *
     * @param Contratohistorico $contratohistorico
     * @return void
     */
    public function updated(Contratohistorico $contratohistorico)
    {
        $sisg = (isset($contratohistorico->unidade->sisg)) ? $contratohistorico->unidade->sisg : '';

        $historico = Contratohistorico::where('contrato_id', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura', 'ASC')
            ->get();

        $cronograma = Contratocronograma::where('contrato_id', $contratohistorico->contrato_id)
            ->delete();


//        $publicacao = ContratoPublicacoes::updateOrCreate([
//            [
//                'contratohistorico_id' => $contratohistorico->id,
//                'status_publicacao_id' => $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR'),
//            ],
//            [
//                'data_publicacao' => $contratohistorico->data_publicacao,
//                'texto_dou' => '',//@DiarioOficialClass::retornaTextoretificacao($contratohistorico),
//                'tipo_pagamento_id' => $this->retornaIdCodigoItem('Forma Pagamento', 'Isento'),
//                'motivo_isencao' => ($sisg) ? $this->retornaIdCodigoItem('Motivo Isenção', 'Atos oficiais administrativos, normativos e de pessoal dos ministérios e órgãos subordinados') : ''
//            ]
//        ]);


        $this->contratocronograma->atualizaCronogramaFromHistorico($historico);
        $this->atualizaContrato($historico);
        $this->atualizaMinutasContrato($contratohistorico);
        $this->createEventCalendar($contratohistorico);
    }


    /**
     * Handle the contratohistorico "deleted" event.
     *
     * @param Contratohistorico $contratohistorico
     * @return void
     */
    public function deleted(Contratohistorico $contratohistorico)
    {

        $historico = Contratohistorico::where('contrato_id', '=', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura', 'ASC')
            ->get();

        $cronograma = Contratocronograma::where('contrato_id', $contratohistorico->contrato_id)
            ->delete();

//        $contratohistorico->cronograma()->delete();
        $this->contratocronograma->atualizaCronogramaFromHistorico($historico);
        $this->atualizaContrato($historico);
    }

    /**
     * Handle the contratohistorico "restored" event.
     *
     * @param Contratohistorico $contratohistorico
     * @return void
     */
    public function restored(Contratohistorico $contratohistorico)
    {
        //
    }

    /**
     * Handle the contratohistorico "force deleted" event.
     *
     * @param Contratohistorico $contratohistorico
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

            if ($tipo instanceof Codigoitem) {
                $array = $this->retornaArrayContratoHistorico($tipo, $arrayhistorico, $contrato_id);

                $contrato = new Contrato();
                $contrato->atualizaContratoFromHistorico($contrato_id, $array);
            }
        }
    }

    public function retornaArrayContratoHistorico(Codigoitem $tipo, array $arrayhistorico, $contrato_id)
    {
        switch ($tipo->descricao) {
            case 'Termo de Rescisão':
                return $this->retornaArrayRescisao($arrayhistorico);
                break;
            case 'Termo Aditivo':
                return $this->retornaArrayAditivo($arrayhistorico, $contrato_id);
                break;
            case 'Termo de Apostilamento':
                return $this->retornaArrayApostilamento($arrayhistorico);
                break;
            default:
                return $this->retornaArrayDefault($arrayhistorico);
        }
    }

    public function retornaArrayAditivo(array $arrayhistorico, $contrato_id)
    {

        $novo_valor = $arrayhistorico['valor_global'];

        if ($arrayhistorico['supressao'] == 'S') {
            $contrato = Contrato::find($contrato_id);
            $novo_valor = $contrato->valor_global - $novo_valor;
        }

        $arrayAditivo = [
            'fornecedor_id' => $arrayhistorico['fornecedor_id'],
            'unidade_id' => $arrayhistorico['unidade_id'],
            'info_complementar' => $arrayhistorico['info_complementar'],
//            'vigencia_inicio' => $arrayhistorico['vigencia_inicio'],
            'vigencia_fim' => $arrayhistorico['vigencia_fim'],
            'valor_global' => $novo_valor,
            'num_parcelas' => $arrayhistorico['num_parcelas'],
            'valor_parcela' => $arrayhistorico['valor_parcela']
        ];
        (isset($arrayhistorico['situacao'])) ? $arrayAditivo['situacao'] = $arrayhistorico['situacao'] : "";
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
        (isset($arrayhistorico['situacao'])) ? $arrayApostilamento['situacao'] = $arrayhistorico['situacao'] : "";
        return $arrayApostilamento;
    }

    public function retornaArrayRescisao(array $arrayhistorico)
    {
        $arrayRescisao = [
            'vigencia_fim' => $arrayhistorico['vigencia_fim'],
            'situacao' => $arrayhistorico['situacao'],
        ];
        return $arrayRescisao;
    }

    public function retornaArrayDefault(array $arrayhistorico)
    {
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
        unset($arrayhistorico['retroativo_soma_subtrai']);


        $arrayDefault = array_filter($arrayhistorico, function ($a) {
            return trim($a) !== "";
        });

        if (isset($arrayhistorico['situacao'])) {
            $arrayDefault['situacao'] = $arrayhistorico['situacao'];
        }

        return $arrayDefault;
    }

    public function createEventCalendar(Contratohistorico $contratohistorico)
    {
        $contrato = Contrato::find($contratohistorico->contrato_id);

        $fornecedor = $contrato->fornecedor->cpf_cnpj_idgener . ' - ' . $contrato->fornecedor->nome;
        $ug = $contrato->unidade->codigo . ' - ' . $contrato->unidade->nomeresumido;

        $tituloinicio = 'Início Vigência Contrato: ' . $contrato->numero . ' Fornecedor: ' . $fornecedor . ' da UG: ' . $ug;
        $titulofim = 'Fim Vigência Contrato: ' . $contrato->numero . ' Fornecedor: ' . $fornecedor . ' da UG: ' . $ug;

        $events = [
            [
                'title' => $tituloinicio,
                'start_date' => new DateTime($contrato->vigencia_inicio),
                'end_date' => new DateTime($contrato->vigencia_inicio),
                'unidade_id' => $contrato->unidade_id
            ],
            [
                'title' => $titulofim,
                'start_date' => new DateTime($contrato->vigencia_fim),
                'end_date' => new DateTime($contrato->vigencia_fim),
                'unidade_id' => $contrato->unidade_id
            ]

        ];

        foreach ($events as $e) {
            $calendario = new CalendarEvent();
            $calendario->insertEvents($e);
        }

        return $calendario;
    }

    private function setSituacao($sisg, $data)
    {
        $data = Carbon::createFromFormat('Y-m-d', $data);

        $situacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Situacao Publicacao');
        })
            ->select('codigoitens.id');

        if ($data->lte(Carbon::now())) {
            return $situacao->where('descricao', 'PUBLICADO')->first();
        }

        if ($sisg) {
            return $situacao->where('descricao', 'A PUBLICAR')->first();
        }

        return $situacao->where('descricao', 'INFORMADO')->first();
    }

    private function atualizaMinutasContrato($contratohistorico)
    {
        $contrato = Contrato::find($contratohistorico->contrato_id);
        $contrato->minutasempenho()->detach();

        //todas minutas que serão vinculadas
        $arrContratoHistoricoMinutaEmpenho = ContratoHistoricoMinutaEmpenho::where('contrato_historico_id','=', $contratohistorico->id)->get();

        // vincula os empenhos ao contrato
        foreach ($arrContratoHistoricoMinutaEmpenho as $contratoHistoricoMinutaEmpenho) {
            $contrato->minutasempenho()->attach($contratoHistoricoMinutaEmpenho->minuta_empenho_id);
        }
    }
}
