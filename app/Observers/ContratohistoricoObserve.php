<?php

namespace App\Observers;

use App\Http\Controllers\Publicacao\DiarioOficialClass;
use App\Http\Traits\BuscaCodigoItens;
use App\Http\Traits\DiarioOficial;
use App\Http\Traits\Formatador;
use App\Models\BackpackUser;
use App\Models\CalendarEvent;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use App\Models\ContratoHistoricoMinutaEmpenho;
use App\Models\ContratoPublicacoes;
use DateTime;
use Alert;
use Redirect;
use Route;
use Doctrine\DBAL\Schema\AbstractAsset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ContratohistoricoObserve
{

    use BuscaCodigoItens;
    use Formatador;
    use DiarioOficial;

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

        $historico = Contratohistorico::where('contrato_id', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura', 'ASC')
            ->get();

        $this->contratocronograma->inserirCronogramaFromHistorico($contratohistorico);
        $this->atualizaContrato($historico);
        $this->createEventCalendar($contratohistorico);

        $tipoEmpenho = $this->retornaIdCodigoItem('Tipo de Contrato', 'Empenho');
        $tipoOutros = $this->retornaIdCodigoItem('Tipo de Contrato', 'Outros');

        if ($contratohistorico->tipo_id != $tipoEmpenho && $contratohistorico->tipo_id != $tipoOutros) {

            if($contratohistorico->publicado){
                $this->executaAtualizacaoViaJob($contratohistorico);
                return true;
            }

            if ($contratohistorico->publicado != true) {
                $this->criaNovaPublicacao($contratohistorico, $this->removeMascaraCPF(backpack_user()->cpf),true);
            }

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

       $historico = Contratohistorico::where('contrato_id', $contratohistorico->contrato_id)
            ->orderBy('data_assinatura', 'ASC')
            ->get();
        $cronograma = Contratocronograma::where('contrato_id', $contratohistorico->contrato_id)
            ->delete();

        $this->contratocronograma->atualizaCronogramaFromHistorico($historico);
        $this->atualizaContrato($historico);
        $this->atualizaMinutasContrato($contratohistorico);
        $this->createEventCalendar($contratohistorico);


        //-------------------------------------------JOB-----------------------------------------------------------
        if ($contratohistorico->publicado && ($contratohistorico->publicacao->count() == 0)) {
            $this->executaAtualizacaoViaJob($contratohistorico);
            return true;
        }
        //-------------------------------------------------------------------------------------------------------------
        if (!is_null($contratohistorico->publicacao) && ($contratohistorico->publicado != true)) {
            if($this->booCampoPublicacaoAlterado($contratohistorico)) {
                $this->trataAtualizacaoPublicacoes($contratohistorico);
            }
        }

    }


    private function trataAtualizacaoPublicacoes($contratohistorico)
    {
        $sisg = (isset($contratohistorico->unidade->sisg)) ? $contratohistorico->unidade->sisg : '';

        $cpf = $this->removeMascaraCPF(backpack_user()->cpf);

        if(($contratohistorico->publicacao->count() == 0)){
            $this->criaNovaPublicacao($contratohistorico,$cpf,true);
            return true;
        }

        if(($contratohistorico->publicacao->count() == 1)){
            $publicacao = ContratoPublicacoes::where('contratohistorico_id',$contratohistorico->id)->first();
            $this->verificaStatusPublicacao($publicacao,$contratohistorico,$sisg,$cpf);
            return true;
        }

        if(($contratohistorico->publicacao->count() > 1)){
            $publicacao = ContratoPublicacoes::where('contratohistorico_id',$contratohistorico->id)->latest()->first();
            $this->verificaStatusPublicacao($publicacao,$contratohistorico,$sisg,$cpf);
            return true;
        }

    }


    public function verificaStatusPublicacao($publicacao,$contratohistorico,$sisg,$cpf)
    {

        $importado = $this->verificaPublicacaoImportada($publicacao,$contratohistorico,$sisg,$cpf);

        if(!$importado) {

            switch ($publicacao->status_publicacao_id) {
                case $this->retornaIdCodigoItem('Situacao Publicacao', 'PUBLICADO'):
                    $this->criaRetificacao($contratohistorico, $sisg,$cpf);
                    break;
                case $this->retornaIdCodigoItem('Situacao Publicacao', 'A PUBLICAR'):
                case $this->retornaIdCodigoItem('Situacao Publicacao', 'TRANSFERIDO PARA IMPRENSA'):
                    $this->verificaStatusOnline($publicacao, $contratohistorico, $sisg,$cpf);
                    break;
                default;
                    //todo verficar porque não redireciona.
                    return redirect('/gescon/contrato/' . $contratohistorico->contrato_id . '/publicacao');
            }
        }

    }


    public function verificaPublicacaoImportada($publicacao,$contratohistorico,$sisg,$cpf)
    {
        $retorno = false;

        $publicado = $this->retornaIdCodigoItem('Situacao Publicacao', 'PUBLICADO');

        if (($publicacao->status == "Importado") && ($publicacao->status_publicacao_id == $publicado)) {

            $this->criaRetificacao($contratohistorico,$sisg,$cpf);

            $retorno = true;
        }
        return $retorno;
    }


    private function executaAtualizacaoViaJob($contratohistorico)
    {
        $publicado_id = $this->retornaIdTipoPublicado();

        $publicacao = ContratoPublicacoes::Create(
            [
                'contratohistorico_id' => $contratohistorico->id,
                'status_publicacao_id' => $publicado_id,
                'data_publicacao' => $contratohistorico->data_publicacao,
                'texto_dou' => '',
                'status' => 'Importado',
                'tipo_pagamento_id' => $this->retornaIdCodigoItem('Forma Pagamento', 'Isento'),
                'motivo_isencao' => 0
            ]
        );
        return $publicacao;
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
            'valor_parcela' => $arrayhistorico['valor_parcela'],
            'publicado' =>  $arrayhistorico['publicado'],
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
            'valor_parcela' => $arrayhistorico['valor_parcela'],
            'publicado' =>  $arrayhistorico['publicado'],
        ];
        (isset($arrayhistorico['situacao'])) ? $arrayApostilamento['situacao'] = $arrayhistorico['situacao'] : "";
        return $arrayApostilamento;
    }

    public function retornaArrayRescisao(array $arrayhistorico)
    {
        $arrayRescisao = [
            'vigencia_fim' => $arrayhistorico['vigencia_fim'],
            'situacao' => $arrayhistorico['situacao'],
            'publicado' =>  $arrayhistorico['publicado'],
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

    private function getSituacao($sisg, $data = null,$create = false)
    {

        $situacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Situacao Publicacao');
        })
            ->select('codigoitens.id');

        if($create) {
            $data = Carbon::createFromFormat('Y-m-d', $data);
            if ($data->lte(Carbon::now())) {
                return $situacao->where('descricao', 'PUBLICADO')->first();
            }
        }

        if ($sisg) {
            return $situacao->where('descricao', 'A PUBLICAR')->first();
        }
        return $situacao->where('descricao', 'INFORMADO')->first();
    }

    private function retornaIdTipoPublicado()
    {
        return Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Situacao Publicacao');
        })->where('descricao', 'PUBLICADO')->first()->id;
    }

    private function verificaStatusOnline($publicacao,$contratohistorico,$sisg,$cpf)
    {
        $diarioOficial = new DiarioOficialClass();
        $diarioOficial->atualizaStatusPublicacao($publicacao);

        (!is_null($publicacao->materia_id))
            ? $this->statusTransferidoParaImprensa($publicacao, $cpf, $contratohistorico, $sisg)
            : $this->statusAPublicar($publicacao,$cpf,$contratohistorico,$sisg);
    }

    private function statusTransferidoParaImprensa($publicacao,$cpf,$contratohistorico,$sisg)
    {

        $devolvido = $this->retornaIdCodigoItem('Situacao Publicacao', 'DEVOLVIDO PELA IMPRENSA');
        $sustada = $this->retornaIdCodigoItem('Situacao Publicacao', 'MATERIA SUSTADA');
        $publicado = $this->retornaIdCodigoItem('Situacao Publicacao', 'PUBLICADO');
        $transferido = $this->retornaIdCodigoItem('Situacao Publicacao', 'TRANSFERIDO PARA IMPRENSA');


        if ($publicacao->status_publicacao_id == $transferido) {
            $diarioOficial = new DiarioOficialClass();
            $retorno = $diarioOficial->sustaMateriaPublicacao($publicacao);

            if ($retorno->out->validaSustacao == "OK") {

                $publicacao->status = 'MATERIA SUSTADA';
                $publicacao->status_publicacao_id = $sustada;
                $publicacao->save();

                $statusPublicacao = ContratoPublicacoes::where('contratohistorico_id', $contratohistorico->id)
                    ->where('status_publicacao_id', $publicado)->first();

                //se houver alguma publicação com status publicado para esse instrumento CRIA RETIFICAÇÃO senão CRIANOVAPUBLICACAO
                (!is_null($statusPublicacao)) ? $this->criaRetificacao($contratohistorico, $sisg,$cpf)
                    : $this->criaNovaPublicacao($contratohistorico,$cpf);

            } else {
                $publicacao->status = 'ERRO AO TENTAR SUSTAR MATERIA';
                $publicacao->log = $retorno->out->validaSustacao;
                $publicacao->status_publicacao_id = $devolvido;
                $publicacao->save();
            }
        }
    }


    private function statusAPublicar($publicacao,$cpf,$contratohistorico,$sisg)
    {
        $publicado = $this->retornaIdCodigoItem('Situacao Publicacao', 'PUBLICADO');
        $statusPublicacao = ContratoPublicacoes::where('contratohistorico_id',$contratohistorico->id)
                ->where('status_publicacao_id',$publicado)->first();

        (!is_null($statusPublicacao)) ? $this->criaRetificacao($contratohistorico,$sisg,$cpf) : $this->criaNovaPublicacao($contratohistorico,$cpf);

    }



    private function atualizaMinutasContrato($contratohistorico)
    {
        // tipos que são permitidos manipular as minutas de empenho do contrato
        $tiposPermitidos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->where('descricao', '<>', 'Termo de Rescisão')
            ->orderBy('descricao')
            ->pluck('id')
            ->toArray();

        if (in_array($contratohistorico->tipo_id, $tiposPermitidos)) {
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
}
