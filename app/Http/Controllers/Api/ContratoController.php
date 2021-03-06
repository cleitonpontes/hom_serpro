<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\APIController;
use App\Http\Traits\Formatador;
use App\Models\Orgao;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\BinaryOp\Concat;
use function foo\func;
use App\Models\Unidade;
use App\Models\Contrato;
use App\Models\Contratoitem;
use App\Models\Contratofatura;
use App\Models\Contratoarquivo;
use App\Models\Contratoempenho;
use App\Models\Contratogarantia;
use App\Models\Contratopreposto;
use App\Models\Contratohistorico;
use App\Models\Contratocronograma;
use App\Models\Contratoocorrencia;
use App\Models\Contratoresponsavel;
use App\Http\Controllers\Controller;
use App\Models\Contratoterceirizado;
use App\Models\Contratodespesaacessoria;
use OpenApi\Annotations as OA;
use App\Models\MinutaEmpenho;
use Illuminate\Support\Facades\Route;
use App\Models\Empenho;
use App\Models\Fornecedor;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Throwable;
use JWTAuth;

class ContratoController extends APIController
{

    public function index(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term) {

            $results = Contrato::select(DB::raw("CONCAT(unidades.codigo,' | ',contratos.numero,' | ',fornecedores.cpf_cnpj_idgener,' - ',fornecedores.nome) AS numero"), 'contratos.id')
                ->distinct()
                ->where(
                    [
                        ['contratos.unidade_id', '=', session()->get('user_ug_id')],
                        ['contratos.situacao', '=', true],
                        ['contratos.unidadecompra_id', '<>', null],
                        ['contratos.numero', 'LIKE', "%$search_term%"]
                    ]
                )
                ->orWhere('contratounidadesdescentralizadas.unidade_id', '=', session()->get('user_ug_id'))
                ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
                ->join('unidades', 'unidades.id', '=', 'contratos.unidadeorigem_id')
                ->leftJoin('contratounidadesdescentralizadas', 'contratounidadesdescentralizadas.contrato_id', '=', 'contratos.id')
//                ->orderby('fornecedores.nome', 'asc')
                ->paginate(20);

            return $results;
        }


//


    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista de org??os com contratos ativos",
     *     description="Retorna um Json de org??os com contratos ativos",
     *     path="/api/contrato/orgaos",
     *     path="/api/contrato/orgaos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de org??os com contratos ativos retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Orgao_id")
     *         ),
     *     )
     * )
     */
    public function orgaosComContratosAtivos()
    {
        return json_encode($this->buscaOrgaosComContratosAtivos());
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista de unidades com contratos ativos",
     *     description="Retorna um Json de unidades com contratos ativos",
     *     path="/api/contrato/unidades",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de unidades com contratos ativos retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Unidade")
     *         ),
     *     )
     * )
     */
    public function unidadesComContratosAtivos()
    {
        return json_encode($this->buscaUnidadesComContratosAtivos());
    }
        /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna os cronogramas",
     *     description="Retorna um Json dos cronogramas",
     *     path="/api/contratos/cronogramas",
     *     @OA\Response(
     *         response=200,
     *         description="Cronogramas retornados com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Cronograma")
     *         ),
     *     )
     * )
     */
    public function cronogramas(Request $request)
    {   
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        
        $cronograma_array = [];
        $cronogramas = (new Contratocronograma())->buscaCronogramasAPI($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        
        foreach ($cronogramas as $cronograma) {
            $cronograma_array[] = $cronograma->cronogramaAPI();
        }
        
        return json_encode($cronograma_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna o cronograma do contrato",
     *     description="Retorna um Json do cronograma do contrato",
     *     path="/api/contrato/{contrato_id}/cronograma",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cronograma do contrato retornado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Cronograma")
     *         ),
     *     )
     * )
     */
    public function cronogramaPorContratoId(int $contrato_id, Request $request)
    {
        $cronograma_array = [];
        $cronogramas = (new Contratocronograma())->buscaCronogramasPorContratoIdAPI($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($cronogramas as $cronograma) {
            $cronograma_array[] = $cronograma->cronogramaAPI();
        }

        return json_encode($cronograma_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os empenhos",
     *     description="Retorna um Json de empenhos",
     *     path="/api/contrato/empenhos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de empenhos retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Empenhos_id")
     *         ),
     *     )
     * )
     */
    public function empenhosPorContratos(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $empenhos_array = [];
        $emp = new Contratoempenho();
        $empenhos = $emp->buscaTodosEmpenhosContratosAtivos($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($empenhos as $e) {
            $empenhos_array[] = [
                'id' => @$e->empenho->id,
                'contrato_id' => $e->contrato->id,
                'numero' => @$e->empenho->numero,
                'credor' => @$e->fornecedor->cpf_cnpj_idgener . ' - ' . @$e->fornecedor->nome ?? '',
                'fonte_recurso' => @$e->empenho->fonte,
                'programa_trabalho' => @$e->empenho->programa_trabalho,
                'planointerno' => @$e->empenho->planointerno->codigo . ' - ' . @$e->empenho->planointerno->descricao ?? '',
                'naturezadespesa' => @$e->empenho->naturezadespesa->codigo . ' - ' . @$e->empenho->naturezadespesa->descricao,
                'empenhado' => number_format(@$e->empenho->empenhado, 2, ',', '.'),
                'aliquidar' => number_format(@$e->empenho->aliquidar, 2, ',', '.'),
                'liquidado' => number_format(@$e->empenho->liquidado, 2, ',', '.'),
                'pago' => number_format(@$e->empenho->pago, 2, ',', '.'),
                'rpinscrito' => number_format(@$e->empenho->rpinscrito, 2, ',', '.'),
                'rpaliquidar' => number_format(@$e->empenho->rpaliquidar, 2, ',', '.'),
                'rpliquidado' => number_format(@$e->empenho->rpliquidado, 2, ',', '.'),
                'rppago' => number_format(@$e->empenho->rppago, 2, ',', '.'),
            ];
        }

        return json_encode($empenhos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os empenhos do contrato",
     *     description="Retorna um Json de empenhos do contrato",
     *     path="/api/contrato/{contrato_id}/empenhos",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de empenhos do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Empenhos")
     *         ),
     *     )
     * )
     */
    public function empenhosPorContratoId(int $contrato_id, Request $request)
    {
        $empenhos_array = [];
        $empenhos = $this->buscaEmpenhosPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($empenhos as $e) {

            $numeroEmpenho = @$e->empenho->unidade->codigo . @$e->empenho->unidade->gestao . @$e->empenho->numero;

            $empenhos_array[] = [
                'unidade_gestora' => @$e->empenho->unidade->codigo,
                'gestao' => @$e->empenho->unidade->gestao,
                'numero' => @$e->empenho->numero,
                'credor' => @$e->fornecedor->cpf_cnpj_idgener . ' - ' . @$e->fornecedor->nome ?? '',
                'fonte_recurso' => @$e->empenho->fonte,
                'programa_trabalho' => @$e->empenho->programa_trabalho,
                'planointerno' => @$e->empenho->planointerno->codigo . ' - ' . @$e->empenho->planointerno->descricao ?? '',
                'naturezadespesa' => @$e->empenho->naturezadespesa->codigo . ' - ' . @$e->empenho->naturezadespesa->descricao,
                'empenhado' => number_format(@$e->empenho->empenhado, 2, ',', '.'),
                'aliquidar' => number_format(@$e->empenho->aliquidar, 2, ',', '.'),
                'liquidado' => number_format(@$e->empenho->liquidado, 2, ',', '.'),
                'pago' => number_format(@$e->empenho->pago, 2, ',', '.'),
                'rpinscrito' => number_format(@$e->empenho->rpinscrito, 2, ',', '.'),
                'rpaliquidar' => number_format(@$e->empenho->rpaliquidar, 2, ',', '.'),
                'rpliquidado' => number_format(@$e->empenho->rpliquidado, 2, ',', '.'),
                'rppago' => number_format(@$e->empenho->rppago, 2, ',', '.'),
                'links' => [
                    'documento_pagamento' => env('API_STA_HOST') . '/api/ordembancaria/empenho/' . $numeroEmpenho
                ]
            ];
        }

        return json_encode($empenhos_array);
    }
    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os historicos do contrato",
     *     description="Retorna um Json de historicos do contrato",
     *     path="/api/contrato/{contrato_id}/historico",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de historicos do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Historicos")
     *         ),
     *     )
     * )
     */
    public function historicos(Request $request)
    {   
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $historico_array = [];
        $historicos = (new Contratohistorico())->buscaHistoricos($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        
        foreach ($historicos as $historico) {
            $historico_array[] = $historico->historicoAPI();
        }

        return json_encode($historico_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os historicos do contrato",
     *     description="Retorna um Json de historicos do contrato",
     *     path="/api/contrato/{contrato_id}/historico",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de historicos do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Historicos")
     *         ),
     *     )
     * )
     */
    public function historicoPorContratoId(int $contrato_id, Request $request)
    {
        $historico_array = [];
        $historicos = (new Contratohistorico())->buscaHistoricoPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($historicos as $historico) {
            $historico_array[] = $historico->historicoAPI();
        }

        return json_encode($historico_array);
    }

        /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as garantias do contrato",
     *     description="Retorna um Json de garantias do contrato",
     *     path="/api/contrato/{contrato_id}/garantias",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de garantias do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Garantias")
     *         ),
     *     )
     * )
     */
    public function garantias(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $garantias_array = [];
        $garantias = (new Contratogarantia())->buscaGarantias($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($garantias as $garantia) {

            $garantias_array[] = $garantia->garantiaAPI();

        }

        return json_encode($garantias_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as garantias do contrato",
     *     description="Retorna um Json de garantias do contrato",
     *     path="/api/contrato/{contrato_id}/garantias",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de garantias do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Garantias")
     *         ),
     *     )
     * )
     */
    public function garantiasPorContratoId(int $contrato_id, Request $request)
    {
        $garantias_array = [];
        $garantias = (new Contratogarantia())->buscaGarantiasPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($garantias as $garantia) {

            $garantias_array[] = $garantia->garantiaAPI();

        }

        return json_encode($garantias_array);
    }

        /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os itens do contrato",
     *     description="Retorna um Json de itens do contrato",
     *     path="/api/contrato/{contrato_id}/itens",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de itens do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Itens")
     *         ),
     *     )
     * )
     */
    public function itens(Request $request)
    {   
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $itens_array = [];
        $itens = (new Contratoitem())->buscaItens($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($itens as $item) {
            $itens_array[] = $item->itemAPI();
        }

        return json_encode($itens_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os itens do contrato",
     *     description="Retorna um Json de itens do contrato",
     *     path="/api/contrato/{contrato_id}/itens",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de itens do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Itens")
     *         ),
     *     )
     * )
     */
    public function itensPorContratoId(int $contrato_id, Request $request)
    {
        $itens_array = [];
        $itens = (new Contratoitem())->buscaItensPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($itens as $item) {
            $itens_array[] = $item->itemAPI();
        }

        return json_encode($itens_array);
    }

        /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os prepostos do contrato",
     *     description="Retorna um Json de prepostos do contrato",
     *     path="/api/contrato/{contrato_id}/prepostos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de prepostos do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Prepostos")
     *         ),
     *     )
     * )
     */
    public function prepostos(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $prepostos_array = [];
        $prepostos = (new Contratopreposto())->buscaPrepostos($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();

        foreach ($prepostos as $preposto) {
            $prepostos_array[] = $preposto->prepostoAPI($this->usuarioTransparencia($preposto->nome, $preposto->cpf, $dadosAbertos));
        }

        return json_encode($prepostos_array);
    }
    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os prepostos do contrato",
     *     description="Retorna um Json de prepostos do contrato",
     *     path="/api/contrato/{contrato_id}/prepostos",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de prepostos do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Prepostos")
     *         ),
     *     )
     * )
     */
    public function prepostosPorContratoId(int $contrato_id, Request $request)
    {
        $prepostos_array = [];
        $prepostos = (new Contratopreposto())->buscaPrepostosPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();

        foreach ($prepostos as $preposto) {
            $prepostos_array[] = $preposto->prepostoAPI($this->usuarioTransparencia($preposto->nome, $preposto->cpf, $dadosAbertos));
        }

        return json_encode($prepostos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os responsaveis",
     *     description="Retorna um Json de responsaveis",
     *     path="/api/contrato/responsaveis",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de responsaveis do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Responsaveis")
     *         ),
     *     )
     * )
     */
    public function responsaveis(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $responsaveis_array = [];
        $responsaveis = (new Contratoresponsavel())->buscaResponsaveis($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();
        foreach ($responsaveis as $responsavel) {
            $responsaveis_array[] = $responsavel->responsavelAPI($this->usuarioTransparencia($responsavel->user->name, $responsavel->user->cpf, $dadosAbertos));
        }

        return json_encode($responsaveis_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os responsaveis do contrato",
     *     description="Retorna um Json de responsaveis do contrato",
     *     path="/api/contrato/{contrato_id}/responsaveis",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de responsaveis do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Responsaveis")
     *         ),
     *     )
     * )
     */
    public function responsaveisPorContratoId(int $contrato_id, Request $request)
    {
        $responsaveis_array = [];
        $responsaveis = (new Contratoresponsavel())->buscaResponsaveisPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();

        foreach ($responsaveis as $responsavel) {

            $responsaveis_array[] = $responsavel->responsavelAPI($this->usuarioTransparencia($responsavel->user->name, $responsavel->user->cpf, $dadosAbertos));
        
        }
        return json_encode($responsaveis_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as despesas acessorias do contrato",
     *     description="Retorna um Json de despesas acessorias do contrato",
     *     path="/api/contrato/{contrato_id}/despesas_acessorias",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de despesas acessorias do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/DespesasAcessorias")
     *         ),
     *     )
     * )
     */
    public function despesasAcessorias(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $despesasAcessorias_array = [];
        $despesasAcessorias = (new Contratodespesaacessoria())->buscaDespesasAcessorias($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        
        foreach ($despesasAcessorias as $despesaAcessoria) {
            $despesasAcessorias_array[] = $despesaAcessoria->despesaAcessoriaAPI();
        }
        
        return json_encode($despesasAcessorias_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as despesas acessorias do contrato",
     *     description="Retorna um Json de despesas acessorias do contrato",
     *     path="/api/contrato/{contrato_id}/despesas_acessorias",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de despesas acessorias do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/DespesasAcessorias")
     *         ),
     *     )
     * )
     */
    public function despesasAcessoriasPorContratoId(int $contrato_id, Request $request)
    {
        $despesasAcessorias_array = [];
        $despesasAcessorias = (new Contratodespesaacessoria())->buscaDespesasAcessoriasPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($despesasAcessorias as $despesaAcessoria) {
            $despesasAcessorias_array[] = $despesaAcessoria->despesaAcessoriaAPI();
        }

        return json_encode($despesasAcessorias_array);
    }

        /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as faturas do contrato",
     *     description="Retorna um Json de faturas do contrato",
     *     path="/api/contrato/{contrato_id}/faturas",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de faturas do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Faturas")
     *         ),
     *     )
     * )
     */
    public function faturas(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $faturas_array = [];
        $faturas = (new Contratofatura())->buscaFaturas($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($faturas as $fatura) {
            $faturas_array[] = $fatura->faturaAPI();
        }

        return json_encode($faturas_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as faturas do contrato",
     *     description="Retorna um Json de faturas do contrato",
     *     path="/api/contrato/{contrato_id}/faturas",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de faturas do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Faturas")
     *         ),
     *     )
     * )
     */
    public function faturasPorContratoId(int $contrato_id, Request $request)
    {
        $faturas_array = [];
        $faturas = (new Contratofatura())->buscaFaturasPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($faturas as $fatura) {
            $faturas_array[] = $fatura->faturaAPI();
        }

        return json_encode($faturas_array);
    }

        /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as ocorrencias do contrato",
     *     description="Retorna um Json de ocorrencias do contrato",
     *     path="/api/contrato/{contrato_id}/ocorrencias",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de ocorrencias do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Ocorrencias")
     *         ),
     *     )
     * )
     */
    public function ocorrencias(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $ocorrencias_array = [];
        $ocorrencias = (new Contratoocorrencia())->buscaOcorrencias($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();

        foreach ($ocorrencias as $ocorrencia) {
            $ocorrencias_array[] = $ocorrencia->ocorrenciaAPI($this->usuarioTransparencia($ocorrencia->usuario->name, $ocorrencia->usuario->cpf, $dadosAbertos));
        }

        return json_encode($ocorrencias_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas as ocorrencias do contrato",
     *     description="Retorna um Json de ocorrencias do contrato",
     *     path="/api/contrato/{contrato_id}/ocorrencias",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de ocorrencias do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Ocorrencias")
     *         ),
     *     )
     * )
     */
    public function ocorrenciasPorContratoId(int $contrato_id, Request $request)
    {
        $ocorrencias_array = [];
        $ocorrencias = (new Contratoocorrencia())->buscaOcorrenciasPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();

        foreach ($ocorrencias as $ocorrencia) {
            $ocorrencias_array[] = $ocorrencia->ocorrenciaAPI($this->usuarioTransparencia($ocorrencia->usuario->name, $ocorrencia->usuario->cpf, $dadosAbertos));
        }

        return json_encode($ocorrencias_array);
    }

        /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os terceirizados do contrato",
     *     description="Retorna um Json de terceirizados do contrato",
     *     path="/api/contrato/{contrato_id}/terceirizados",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de terceirizados do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Terceirizados")
     *         )
     *     )
     * )
     */
    public function terceirizados(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $terceirizados_array = [];
        $terceirizados = (new Contratoterceirizado())->buscaTerceirizados($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();

        foreach ($terceirizados as $terceirizado) {
            $terceirizados_array[] = $terceirizado->terceirizadoAPI($this->usuarioTransparencia($terceirizado->nome, $terceirizado->cpf, $dadosAbertos));
        }

        return json_encode($terceirizados_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os terceirizados do contrato",
     *     description="Retorna um Json de terceirizados do contrato",
     *     path="/api/contrato/{contrato_id}/terceirizados",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de terceirizados do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Terceirizados")
     *         )
     *     )
     * )
     */
    public function terceirizadosPorContratoId(int $contrato_id, Request $request)
    {

        $terceirizados_array = [];
        $terceirizados = (new Contratoterceirizado())->buscaTerceirizadosPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $dadosAbertos = $this->dadosAbertos();

        foreach ($terceirizados as $terceirizado) {
            $terceirizados_array[] = $terceirizado->terceirizadoAPI($this->usuarioTransparencia($terceirizado->nome, $terceirizado->cpf, $dadosAbertos));
        }

        return json_encode($terceirizados_array);
    }
    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os arquivos do contrato",
     *     description="Retorna um Json de arquivos do contrato",
     *     path="/api/contrato/{contrato_id}/arquivos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de arquivos do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Arquivos")
     *         ),
     *     )
     * )
     */
    public function arquivos(Request $request)
    {
        $this->rangeObrigatorio($request->dt_alteracao_min, $request->dt_alteracao_max);
        $arquivos_array = [];
        $arquivos = (new Contratoarquivo())->buscaArquivos($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        
        foreach ($arquivos as $arquivo) {
            $arquivos_array[] = $arquivo->arquivoAPI();
        }

        return json_encode($arquivos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os arquivos do contrato",
     *     description="Retorna um Json de arquivos do contrato",
     *     path="/api/contrato/{contrato_id}/arquivos",
     *     @OA\Parameter(
     *         name="contrato_id",
     *         in="path",
     *         description="id do contrato",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de arquivos do contrato retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Arquivos")
     *         ),
     *     )
     * )
     */
    public function arquivosPorContratoId(int $contrato_id, Request $request)
    {
        $arquivos_array = [];
        $arquivos = (new Contratoarquivo())->buscaArquivosPorContratoId($contrato_id, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($arquivos as $arquivo) {
            $arquivos_array[] = $arquivo->arquivoAPI();
        }

        return json_encode($arquivos_array);
    }

    private function buscaOrgaosComContratosAtivos()
    {
        $orgaos = Orgao::select('codigo')
            ->whereHas('unidades', function ($u) {
                $u->whereHas('contratos', function ($c) {
                    $c->where('situacao', true);
                })->where('sigilo', false);
            })
            ->orderBy('codigo');

        return $orgaos->get();
    }

    private function buscaUnidadesComContratosAtivos()
    {
        $unidades = Unidade::select('codigo')
            ->whereHas('contratos', function ($c) {
                $c->where('situacao', true);
            })->where('sigilo', false)
            ->orderBy('codigo');

        return $unidades->get();
    }

    private function buscaEmpenhosPorContratoId(int $contrato_id, $range)
    {
        $empenhos = Contratoempenho::join('contratos', 'contratos.id', '=', 'contratoempenhos.contrato_id')
            ->join('unidades', 'unidades.id', '=', 'contratos.unidade_id')
            ->join('empenhos', 'empenhos.id', '=', 'contratoempenhos.empenho_id')
            ->where('contrato_id', $contrato_id)
            ->where('unidades.sigilo', "=", false)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('empenhos.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $empenhos;
    }


    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os contratos Ativos",
     *     description="Retorna um Json de contratos Ativos",
     *     path="/api/contrato/",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contratos ativos retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Contratos")
     *         ),
     *     )
     * )
     */
    public function contratoAtivoAll(Request $request)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratos($this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        $prefixoAPI = Route::current()->getPrefix();

        foreach ($contratos as $contrato) {
            $contratos_array[] = [
                'id' => $contrato->id,
                'receita_despesa' => ($contrato->receita_despesa) == 'D' ? 'Despesa' : 'Receita',
                'numero' => $contrato->numero,
                'orgao_codigo' => $contrato->unidade->orgao->codigo,
                'orgao_nome' => $contrato->unidade->orgao->nome,
                'unidade_codigo' => $contrato->unidade->codigo,
                'unidade_nome_resumido' => $contrato->unidade->nomeresumido,
                'unidade_nome' => $contrato->unidade->nome,
                'fornecedor_tipo' => $contrato->fornecedor->tipo_fornecedor,
                'fonecedor_cnpj_cpf_idgener' => $contrato->fornecedor->cpf_cnpj_idgener,
                'fornecedor_nome' => $contrato->fornecedor->nome,
                'codigo_tipo' => @$this->tipo->descres,
                'tipo' => $contrato->tipo->descricao,
                'categoria' => $contrato->categoria->descricao,
                'processo' => $contrato->processo,
                'objeto' => $contrato->objeto,
                'fundamento_legal' => @$this->fundamento_legal,
                'informacao_complementar' => $contrato->info_complementar,
                'codigo_modalidade' => @$this->modalidade->descres,
                'modalidade' => $contrato->modalidade->descricao,
                'unidade_compra' => @$this->unidadecompra->codigo,
                'licitacao_numero' => $contrato->licitacao_numero,
                'sistema_origem_licitacao' => @$this->sistema_origem_licitacao,
                'data_assinatura' => $contrato->data_assinatura,
                'data_publicacao' => $contrato->data_publicacao,
                'vigencia_inicio' => $contrato->vigencia_inicio,
                'vigencia_fim' => $contrato->vigencia_fim,
                'valor_inicial' => number_format($contrato->valor_inicial, 2, ',', '.'),
                'valor_global' => number_format($contrato->valor_global, 2, ',', '.'),
                'num_parcelas' => $contrato->num_parcelas,
                'valor_parcela' => number_format($contrato->valor_parcela, 2, ',', '.'),
                'valor_acumulado' => number_format($contrato->valor_acumulado, 2, ',', '.'),
                'link_historico' => url($prefixoAPI . '/' . $contrato->id . '/historico/'),
                'link_empenhos' => url($prefixoAPI . '/' . $contrato->id . '/empenhos/'),
                'link_cronograma' => url($prefixoAPI . '/' . $contrato->id . '/cronograma/'),
                'link_garantias' => url($prefixoAPI . '/' . $contrato->id . '/garantias/'),
                'link_itens' => url($prefixoAPI . '/' . $contrato->id . '/itens/'),
                'link_prepostos' => url($prefixoAPI . '/' . $contrato->id . '/prepostos/'),
                'link_responsaveis' => url($prefixoAPI . '/' . $contrato->id . '/responsaveis/'),
                'link_despesas_acessorias' => url($prefixoAPI . '/' . $contrato->id . '/despesas_acessorias/'),
                'link_faturas' => url($prefixoAPI . '/' . $contrato->id . '/faturas/'),
                'link_ocorrencias' => url($prefixoAPI . '/' . $contrato->id . '/ocorrencias/'),
                'link_terceirizados' => url($prefixoAPI . '/' . $contrato->id . '/terceirizados/'),
                'link_arquivos' => url($prefixoAPI . '/' . $contrato->id . '/arquivos/'),
            ];
        }

        return json_encode($contratos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna um contrato por sua UASG e pelo seu n??mero",
     *     description="Retorna um Json com o contrato por sua UASG e pelo seu n??mero",
     *     path="/api/contrato/ugorigem/{codigo_uasg}/numeroano/{numeroano_contrato}",
     *     @OA\Parameter(
     *         name="codigo_uasg",
     *         in="path",
     *         description="Codigo da UASG",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="numeroano_contrato",
     *         in="path",
     *         description="N??mero e Ano do contrato devem ser inseridos concatenados, sem a barra e com os nove caracteres (utilizando zeros se necess??rio '000292020')",
     *         required=true,
     *         @OA\Schema(
     *                 type="string",
     *                 minLength=9,
     *                 maxLength=9,
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contrato por UASG e pelo n??mero retornado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Contratos_ug")
     *         ),
     *     @OA\Response(
     *         response=422,
     *         description="O par??metro 'numeroano_contrato' foi enviado com um tamanho inv??lido. N??mero e Ano do contrato devem ser inseridos concatenados, sem a barra e com os nove caracteres (utilizando zeros se necess??rio '000292020')",
     *         @OA\JsonContent(
     *              @OA\Property(property="errors", type="string", example="O parametro 'numeroano_contrato' foi enviado com um tamanho invalido"),
     *         )
     *         ),
     *   )
     * )
     */
    public function contratoUASGeContratoAno(string $codigo_uasg, string $numeroano_contrato, Request $request)
    {
        if (strlen($numeroano_contrato) != 9) {
            abort(response()->json(['errors' => "O parametro 'numeroano_contrato' foi enviado com um tamanho invalido",], 422));
        }
        $numeroano_contrato = substr_replace($numeroano_contrato, '/', 5, 0);
        $contratos_array = [];
        $contratos = $this->buscaContratoPorUASGeNumero($codigo_uasg, $numeroano_contrato, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));
        foreach ($contratos as $contrato) {
            $contratos_array[] = $contrato->contratoAPI(Route::current()->getPrefix());
        }
        return json_encode($contratos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os contratos ativos por UG",
     *     description="Retorna um Json de contratos ativos da UG",
     *     path="/api/contrato/ug/{unidade_codigo}",
     *     @OA\Parameter(
     *         name="unidade_codigo",
     *         in="path",
     *         description="codigo da unidade",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contratos ativos da UG retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Contratos_ug")
     *         ),
     *     )
     * )
     */
    public function contratoAtivoPorUg(string $unidade, Request $request)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosPorUg($unidade, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($contratos as $contrato) {
            $contratos_array[] = $contrato->contratoAPI(Route::current()->getPrefix());
        }

        return json_encode($contratos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os contratos ativos por Orgao",
     *     description="Retorna um Json de contratos ativos do orgao",
     *     path="/api/contrato/orgao/{orgao}",
     *     @OA\Parameter(
     *         name="orgao",
     *         in="path",
     *         description="numero do orgao",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contratos ativos do org??o retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Contratos_ug")
     *         ),
     *     )
     * )
     */
    public function contratoAtivoPorOrgao(string $orgao, Request $request)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosPorOrgao($orgao, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($contratos as $contrato) {
            $contratos_array[] = $contrato->contratoAPI(Route::current()->getPrefix());
        }

        return json_encode($contratos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todas os contratos inativos por UG",
     *     description="Retorna um Json de contratos inativos da UG",
     *     path="/api/contrato/inativo/ug/{unidade_codigo}",
     *     @OA\Parameter(
     *         name="unidade_codigo",
     *         in="path",
     *         description="codigo da unidade",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contratos inativos da UG retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Contratos_ug")
     *         ),
     *     )
     * )
     */
    public function contratoInativoPorUg(string $unidade, Request $request)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosInativosPorUg($unidade, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($contratos as $contrato) {
            $contratos_array[] = $contrato->contratoAPI(Route::current()->getPrefix());
        }

        return json_encode($contratos_array);
    }

    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     summary="Retorna uma lista com todos os contratos inativos por Orgao",
     *     description="Retorna um Json de contratos inativos do orgao",
     *     path="/api/contrato/inativo/orgao/{orgao}",
     *     @OA\Parameter(
     *         name="orgao",
     *         in="path",
     *         description="numero do orgao",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contratos inativos do org??o retornada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Contratos_ug")
     *         ),
     *     )
     * )
     */
    public function contratoInativoPorOrgao(string $orgao, Request $request)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosInativosPorOrgao($orgao, $this->range($request->dt_alteracao_min, $request->dt_alteracao_max));

        foreach ($contratos as $contrato) {
            $contratos_array[] = $contrato->contratoAPI(Route::current()->getPrefix());
        }

        return json_encode($contratos_array);
    }

    private function buscaContratosPorUg(string $unidade, $range)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($unidade) {
            $q->whereHas('orgao', function ($o) {
                $o->where('situacao', true);
            })
                ->where('codigo', $unidade)
                ->where('sigilo', false)
                ->where('situacao', true);
        })
            ->where('situacao', true)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('updated_at', [$range[0], $range[1]]);
            })
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaContratosInativosPorUg(string $unidade, $range)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($unidade) {
            $q->whereHas('orgao', function ($o) {
                $o->where('situacao', true);
            })
                ->where('codigo', $unidade)
                ->where('sigilo', false)
                ->where('situacao', true);
        })
            ->where('situacao', false)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('updated_at', [$range[0], $range[1]]);
            })
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaContratosPorOrgao(string $orgao, $range)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($orgao) {
            $q->whereHas('orgao', function ($o) use ($orgao) {
                $o->where('codigo', $orgao)
                    ->where('situacao', true);
            })->where('sigilo', false);
        })
            ->where('situacao', true)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('updated_at', [$range[0], $range[1]]);
            })
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaContratoPorUASGeNumero(string $codigo_uasg, string $numeroano_contrato, $range)
    {
        $contratos = Contrato::whereHas('unidadeorigem', function ($q) use ($codigo_uasg) {
            $q->whereHas('orgao', function ($o) {
                $o->where('situacao', true);
            })
                ->where('sigilo', false)
                ->where('codigo', $codigo_uasg)
                ->where('situacao', true);
        })
            ->where('numero', $numeroano_contrato)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('updated_at', [$range[0], $range[1]]);
            })
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaContratosInativosPorOrgao(string $orgao, $range)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($orgao) {
            $q->whereHas('orgao', function ($o) use ($orgao) {
                $o->where('codigo', $orgao)
                    ->where('situacao', true);
            })->where('sigilo', false);
        })
            ->where('situacao', false)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('updated_at', [$range[0], $range[1]]);
            })
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaContratos($range)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) {
            $q->whereHas('orgao', function ($o) {
                $o->where('situacao', true);
            })->where('sigilo', false);
        })
        ->when($range != null, function ($d) use ($range) {
            $d->whereBetween('updated_at', [$range[0], $range[1]]);
        })
            ->where('situacao', true)
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    public function buscarCamposParaCadastroContratoPorIdEmpenho($id)
    {
        $camposContrato = MinutaEmpenho::select(
            "compras.modalidade_id",
            "minutaempenhos.unidade_id",
            "unidades.codigo",
            "unidades.nomeresumido",
            "amparo_legal.ato_normativo",
            "amparo_legal.artigo",
            "minutaempenhos.amparo_legal_id",
            "compras.numero_ano as compra_numero_ano"
        )
            ->join('compras', 'compras.id', '=', 'minutaempenhos.compra_id')
            ->join('unidades', 'unidades.id', '=', 'minutaempenhos.unidade_id')
            ->join('amparo_legal', 'amparo_legal.id', '=', 'minutaempenhos.amparo_legal_id')
            ->where('minutaempenhos.id', $id)->firstOrFail()->toArray();

        return $camposContrato;
    }

    /**
     *
     * @OA\Components(
     *         @OA\Schema(
     *             schema="Contratos",
     *             type="object",
     *             @OA\Property(property="id",type="integer",example="1"),
     *             @OA\Property(property="receita_despesa",type="string",example="Despesa"),
     *             @OA\Property(property="numero",type="string",example="00420/2019"),
     *             @OA\Property(property="orgao_codigo",type="string",example="63000"),
     *             @OA\Property(property="orgao_nome",type="string",example="ADVOCACIA-GERAL DA UNI??O"),
     *             @OA\Property(property="unidade_codigo",type="string",example="110161"),
     *             @OA\Property(property="unidade_nome_resumido",type="string",example="SAD/DF"),
     *             @OA\Property(property="unidade_nome",type="string",example="SUPERIN. DE ADM. NO DISTRITO FEDERAL"),
     *             @OA\Property(property="fornecedor_tipo",type="string",example="UG"),
     *             @OA\Property(property="fonecedor_cnpj_cpf_idgener",type="integer",example="803010"),
     *             @OA\Property(property="fornecedor_nome",type="string",example="SERPRO REGIONAL BRASILIA"),
     *             @OA\Property(property="tipo",type="string",example="Concess??o"),
     *             @OA\Property(property="categoria",type="string",example="Inform??tica"),
     *             @OA\Property(property="processo",type="string",example="50600.000501/2020-04"),
     *             @OA\Property(property="objeto",type="string",example="O PRESENTE CONTRATO TEM POR OBJETO REGULAR, EXCLUSIVAMENTE, SEGUNDO A ESTRUTURA DA TARIFA DO GRUPO B EM BAIXA TENS??O, O FORNECIMENTO AO CONTRATANTE, PELA BOA VISTA ENERGIA S.A., DA ENERGIA EL??TRICA NECESS??RIA AO FUNCIONAMENTO DE SUAS INSTALA????ES, LOCALIZADAS NAS UNIDADES DA AGU NO ESTADO DE RORAIMA."),
     *             @OA\Property(property="informacao_complementar",type="string",example="null"),
     *             @OA\Property(property="modalidade",type="string",example="Ades??o"),
     *             @OA\Property(property="licitacao_numero",type="string",example="00300/2019"),
     *             @OA\Property(property="data_assinatura",type="string",example="2020-07-31",format="yyyy-mm-dd"),
     *             @OA\Property(property="data_publicacao",type="string",example="2020-08-03",format="yyyy-mm-dd"),
     *             @OA\Property(property="vigencia_inicio",type="string",example="2020-08-04",format="yyyy-mm-dd"),
     *             @OA\Property(property="vigencia_fim",type="string",example="2020-08-28",format="yyyy-mm-dd"),
     *             @OA\Property(property="valor_inicial",type="number",example="1.000.000,00"),
     *             @OA\Property(property="valor_global",type="number",example="1.000.000,00"),
     *             @OA\Property(property="num_parcelas",type="integer",example="10"),
     *             @OA\Property(property="valor_parcela",type="number",example="100.000,00"),
     *             @OA\Property(property="valor_acumulado",type="number",example="1.000.000,00"),
     *             @OA\Property(property="link_historico",type="string",example="http://localhost:8000/api/contrato/1/historico"),
     *             @OA\Property(property="link_empenhos",type="string",example="http://localhost:8000/api/contrato/1/empenhos"),
     *             @OA\Property(property="link_cronograma",type="string",example="http://localhost:8000/api/contrato/1/cronograma"),
     *             @OA\Property(property="link_garantias",type="string",example="http://localhost:8000/api/contrato/1/garantias"),
     *             @OA\Property(property="link_itens",type="string",example="http://localhost:8000/api/contrato/1/itens"),
     *             @OA\Property(property="link_prepostos",type="string",example="http://localhost:8000/api/contrato/1/prepostos"),
     *             @OA\Property(property="link_responsaveis",type="string",example="http://localhost:8000/api/contrato/1/responsaveis"),
     *             @OA\Property(property="link_despesas_acessorias",type="string",example="http://localhost:8000/api/contrato/1/despesas_acessorias"),
     *             @OA\Property(property="link_faturas",type="string",example="http://localhost:8000/api/contrato/1/faturas"),
     *             @OA\Property(property="link_ocorrencias",type="string",example="http://localhost:8000/api/contrato/1/ocorrencias"),
     *             @OA\Property(property="link_terceirizados",type="string",example="http://localhost:8000/api/contrato/1/terceirizados"),
     *             @OA\Property(property="link_arquivos",type="string",example="http://localhost:8000/api/contrato/1/arquivos"),
     *         ),
     *
     *         @OA\Schema(
     *             schema="UG",
     *             type="object",
     *             @OA\Property(property="codigo",type="string",example="110161"),*
     *             @OA\Property(property="nome_resumido",type="string",example="SAD/DF/AGU"),
     *             @OA\Property(property="nome",type="string",example="SUPERINTENDENCIA DE ADM. NO DISTRITO FEDERAL"),
     *         ),
     *
     *         @OA\Schema(
     *             schema="Orgao",
     *             type="object",
     *             @OA\Property(property="codigo",type="string",example="63000"),
     *             @OA\Property(property="nome",type="string",example="ADVOCACIA-GERAL DA UNIAO"),
     *             @OA\Property(property="unidade_gestora", type="object", ref="#/components/schemas/UG"),
     *         ),
     *
     *         @OA\Schema(
     *             schema="Contratante",
     *             type="object",
     *             @OA\Property(property="orgao", type="object", ref="#/components/schemas/Orgao")
     *         ),
     *
     *         @OA\Schema(
     *             schema="Fornecedor",
     *             type="object",
     *             @OA\Property(property="tipo",type="string",example="JURIDICA"),
     *             @OA\Property(property="cnpj_cpf_idgener",type="string",example="02.341.470/0001-44"),
     *             @OA\Property(property="nome", type="string", example="RORAIMA ENERGIA S.A"),
     *         ),
     *
     *         @OA\Schema(
     *             schema="Links",
     *             type="object",
     *             @OA\Property(property="historico",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/historico"),
     *             @OA\Property(property="empenhos",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/empenhos"),
     *             @OA\Property(property="cronograma",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/cronograma"),
     *             @OA\Property(property="garantias",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/garantias"),
     *             @OA\Property(property="itens",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/itens"),
     *             @OA\Property(property="prepostos",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/prepostos"),
     *             @OA\Property(property="responsaveis",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/responsaveis"),
     *             @OA\Property(property="despesas_acessorias",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/despesas_acessorias"),
     *             @OA\Property(property="faturas",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/faturas"),
     *             @OA\Property(property="ocorrencias",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/ocorrencias"),
     *             @OA\Property(property="terceirizados",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/terceirizados"),
     *             @OA\Property(property="arquivos",type="string",example="http://sc-treino.agu.gov.br/api/contrato/2957/arquivos"),
     *         ),
     *
     *         @OA\Schema(
     *             schema="Contratos_ug",
     *             type="object",
     *             @OA\Property(property="id",type="integer",example="2957"),
     *             @OA\Property(property="receita_despesa",type="string",example="Despesa"),
     *             @OA\Property(property="numero",type="string",example="00059/2009"),
     *             @OA\Property(property="contratante", type="object", ref="#/components/schemas/Contratante"),
     *             @OA\Property(property="fornecedor", type="object", ref="#/components/schemas/Fornecedor"),
     *             @OA\Property(property="tipo",type="string",example="Contrato"),
     *             @OA\Property(property="categoria",type="string",example="Compras"),
     *             @OA\Property(property="subcategoria",type="string",example="null"),
     *             @OA\Property(property="unidades_requisitantes",type="string",example="null"),
     *             @OA\Property(property="processo",type="string",example="00549.001460/2008-23"),
     *             @OA\Property(property="objeto",type="string",example="O PRESENTE CONTRATO TEM POR OBJETO REGULAR, EXCLUSIVAMENTE, SEGUNDO A ESTRUTURA DA TARIFA DO GRUPO B EM BAIXA TENS??O, O FORNECIMENTO AO CONTRATANTE, PELA BOA VISTA ENERGIA S.A., DA ENERGIA EL??TRICA NECESS??RIA AO FUNCIONAMENTO DE SUAS INSTALA????ES, LOCALIZADAS NAS UNIDADES DA AGU NO ESTADO DE RORAIMA."),
     *             @OA\Property(property="informacao_complementar",type="string",example=""),
     *             @OA\Property(property="modalidade",type="string",example="Inexigibilidade"),
     *             @OA\Property(property="licitacao_numero",type="string",example="00022/2009"),
     *             @OA\Property(property="data_assinatura",type="string",example="2009-06-23",format="yyyy-mm-dd"),
     *             @OA\Property(property="data_publicacao",type="string",example="2009-06-23",format="yyyy-mm-dd"),
     *             @OA\Property(property="vigencia_inicio",type="string",example="2012-06-23",format="yyyy-mm-dd"),
     *             @OA\Property(property="vigencia_fim",type="string",example="2099-06-30",format="yyyy-mm-dd"),
     *             @OA\Property(property="valor_inicial",type="number",example="43.538,12"),
     *             @OA\Property(property="valor_global",type="number",example="102.000,00"),
     *             @OA\Property(property="num_parcelas",type="integer",example="12"),
     *             @OA\Property(property="valor_parcela",type="number",example="8.500,00"),
     *             @OA\Property(property="valor_acumulado",type="number",example="8.486.798,21"),
     *             @OA\Property(property="links", type="object", ref="#/components/schemas/Links"),
     *         ),
     *
     *         @OA\Schema(
     *             schema="Historicos",
     *             type="object",
     *             @OA\Property(property="receita_despesa",type="string",example="Despesa"),
     *             @OA\Property(property="numero",type="string",example="00420/2019"),
     *             @OA\Property(property="observacao",type="string",example="CELEBRA????O DO CONTRATO: 0006/2017 DE ACORDO COM PROCESSO N??MERO: 00589.000328/2016-38"),
     *             @OA\Property(property="ug",type="string",example="110099"),
     *             @OA\Property(property="fornecedor", type="object", ref="#/components/schemas/Fornecedor"),
     *             @OA\Property(property="tipo",type="string",example="CONCESS??O"),
     *             @OA\Property(property="categoria",type="string",example=""),
     *             @OA\Property(property="processo",type="string",example="00589.000328/2016-38"),
     *             @OA\Property(property="objeto",type="string",example="CONTRATA????O DE SERVI??OS DE PORTEIRO/VIGIA PARA AS UNIDADES DA AGU EM OSASCO, SP, PRESIDENTE PRUDENTE, S??O JOS?? DO RIO PRETO, RIBEIR??O PRETO E MARILIA, CONFORME EDITAL E SEUS ANEXOS."),
     *             @OA\Property(property="informacao_complementar",type="string",example="UNIDADES PSF OSASCO, SAD S??O PAULO (BACEUNAS), PSU PRESIDENTE PRUDENTE, PSU S??O JOS?? DO RIO PRETO, PSU RIBEIR??O PRETO E PSU MAR??LIA. "),
     *             @OA\Property(property="modalidade",type="string",example="Ades??o"),
     *             @OA\Property(property="licitacao_numero",type="string",example="00300/2019"),
     *             @OA\Property(property="data_assinatura",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="data_publicacao",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="vigencia_inicio",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="vigencia_fim",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="valor_inicial",type="number",example="1.200,25"),
     *             @OA\Property(property="valor_global",type="number",example="1.200,25"),
     *             @OA\Property(property="num_parcelas",type="integer",example="10"),
     *             @OA\Property(property="valor_parcela",type="number",example="1.200,25"),
     *             @OA\Property(property="novo_valor_global",type="number",example="1.200,25"),
     *             @OA\Property(property="novo_num_parcelas",type="integer",example="15"),
     *             @OA\Property(property="novo_valor_parcela",type="number",example="1.200,25"),
     *             @OA\Property(property="data_inicio_novo_valor",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="retroativo",type="string",example="N??o"),
     *             @OA\Property(property="retroativo_mesref_de",type="integer",example="01"),
     *             @OA\Property(property="retroativo_anoref_de",type="integer",example="2020"),
     *             @OA\Property(property="retroativo_mesref_ate",type="integer",example="05"),
     *             @OA\Property(property="retroativo_anoref_ate",type="integer",example="2020"),
     *             @OA\Property(property="retroativo_vencimento",type="integer",example="04"),
     *             @OA\Property(property="retroativo_valor",type="number",example="1.200,25"),
     *         ),
     *
     *          @OA\Schema(
     *             schema="Garantias",
     *             type="object",
     *             @OA\Property(property="tipo",type="string",example="Fian??a Banc??ria"),
     *             @OA\Property(property="valor",type="number",example="70.200,25"),
     *             @OA\Property(property="vencimento",type="string",example="2021-01-01",format=" yyyy-mm-dd"),
     *         ),
     *
     *          @OA\Schema(
     *             schema="Itens",
     *             type="object",
     *             @OA\Property(property="tipo_id",type="string",example="Servi??o"),
     *             @OA\Property(property="grupo_id",type="string",example="GRUPO GENERICO SERVICO"),
     *             @OA\Property(property="catmatseritem_id",type="string",example="8729 - PRESTACAO DE SERVICOS DE PORTARIA / RECEPCAO"),
     *             @OA\Property(property="descricao_complementar",type="string",example="null"),
     *             @OA\Property(property="quantidade",type="integer",example="20"),
     *             @OA\Property(property="valorunitario",type="number",example="7.163,26"),
     *             @OA\Property(property="valortotal",type="number",example="143.265,20"),
     *         ),
     *
     *          @OA\Schema(
     *             schema="Prepostos",
     *             type="object",
     *             @OA\Property(property="usuario",type="string",example="***.111.111-** FULANO DE TAL"),
     *             @OA\Property(property="email",type="string",example="email@emailpreposto.com"),
     *             @OA\Property(property="telefonefixo",type="string",example="(61) 9999-8888"),
     *             @OA\Property(property="celular",type="string",example="(61) 91234-5678"),
     *             @OA\Property(property="doc_formalizacao",type="string",example="200"),
     *             @OA\Property(property="informacao_complementar",type="string",example="Informa????es complementares"),
     *             @OA\Property(property="data_inicio",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="data_fim",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="situacao",type="string",example="Ativo"),
     *         ),
     *
     *          @OA\Schema(
     *             schema="Responsaveis",
     *             type="object",
     *             @OA\Property(property="usuario",type="string",example="***.111.111-** FULANO DE TAL"),
     *             @OA\Property(property="funcao_id",type="string",example="Fiscal Administrativo Substituto"),
     *             @OA\Property(property="instalacao_id",type="string",example="DF - Bras??lia - Sede I"),
     *             @OA\Property(property="portaria",type="string",example="PORTARIA N?? 80, DE 06 DE FEVEREIRO DE 2020"),
     *             @OA\Property(property="situacao",type="string",example="Ativo"),
     *             @OA\Property(property="data_inicio",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="data_fim",type="string",example="2021-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="telefone_fixo",type="string",example="(61) 9999-8888"),
     *             @OA\Property(property="telefone_celular",type="string",example="(61) 91234-5678"),
     *         ),
     *
     *          @OA\Schema(
     *             schema="DespesasAcessorias",
     *             type="object",
     *             @OA\Property(property="tipo_id",type="string",example="Garantia Estendida"),
     *             @OA\Property(property="recorrencia_id",type="string",example="Mensal"),
     *             @OA\Property(property="descricao_complementar",type="string",example="DESCRI????O COMPLEMENTAR"),
     *             @OA\Property(property="vencimento",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="valor",type="number",example="1.200,25"),
     *         ),
     *
     *          @OA\Schema(
     *             schema="Faturas",
     *             type="object",
     *             @OA\Property(property="tipolistafatura_id",type="string",example="PRESTA????O DE SERVI??OS"),
     *             @OA\Property(property="justificativafatura_id",type="string",example="Ordem Lista: Seguindo a ordem cronol??gica da lista."),
     *             @OA\Property(property="sfadrao_id",type="string",example=""),
     *             @OA\Property(property="numero",type="string",example="0572PORT/PSUSSR05"),
     *             @OA\Property(property="emissao",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="prazo",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="vencimento",type="string",example="2021-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="valor",type="number",example="16.587,52"),
     *             @OA\Property(property="juros",type="number",example="0,00"),
     *             @OA\Property(property="multa",type="number",example="0,00"),
     *             @OA\Property(property="glosa",type="number",example="0,00"),
     *             @OA\Property(property="valorliquido",type="number",example="16.587,52"),
     *             @OA\Property(property="processo",type="string",example="50600.003651/2015-20"),
     *             @OA\Property(property="protocolo",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="ateste",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="repactuacao",type="string",example="Sim"),
     *             @OA\Property(property="infcomplementar",type="string",example="AUTOM??TICA"),
     *             @OA\Property(property="mesref",type="string",example="01"),
     *             @OA\Property(property="anoref",type="string",example="2020"),
     *             @OA\Property(property="situacao",type="string",example="Pendente"),
     *         ),
     *
     *          @OA\Schema(
     *             schema="Ocorrencias",
     *             type="object",
     *             @OA\Property(property="numero",type="integer",example="10"),
     *             @OA\Property(property="usuario",type="string",example="***.111.111-** FULANO DE TAL"),
     *             @OA\Property(property="data",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="ocorrencia",type="string",example="EXTRATO DE CONTRATO"),
     *             @OA\Property(property="notificapreposto",type="string",example="Sim"),
     *             @OA\Property(property="emailpreposto",type="string",example="email@cconta.com"),
     *             @OA\Property(property="numeroocorrencia",type="integer",example="3"),
     *             @OA\Property(property="novasituacao",type="string",example="Atendida"),
     *             @OA\Property(property="situacao",type="string",example="Atendida Parcial"),
     *             @OA\Property(property="arquivos",type="array", @OA\Items(type="object", example="[{ 'arquivo_1': 'localhost:8000/storage/contrato/1_00420_2019/580e4da71ac02ec0ecf4f09728b51bc0.pdf'},{'arquivo_2': 'localhost:8000/storage/contrato/1_00420_2019/4e35d0c021543920a41402dfaa0ab89b.pdf'}]")),
     *         ),
     *
     *          @OA\Schema(
     *             schema="Terceirizados",
     *             type="object",
     *             @OA\Property(property="usuario",type="string",example="111.111.111-00 FULANO DE TAL"),
     *             @OA\Property(property="funcao_id",type="string",example="Ajudante"),
     *             @OA\Property(property="descricao_complementar",type="string",example="Ajudante de almoxarifado"),
     *             @OA\Property(property="jornada",type="integer",example="12"),
     *             @OA\Property(property="unidade",type="string",example="AGU-SEDE"),
     *             @OA\Property(property="salario",type="number",example="1.200,25"),
     *             @OA\Property(property="custo",type="number",example="0,00"),
     *             @OA\Property(property="escolaridade_id",type="string",example="Superior completo"),
     *             @OA\Property(property="data_inicio",type="string",example="2020-01-01",format=" yyyy-mm-dd"),
     *             @OA\Property(property="data_fim",type="string",example="2020-01-31",format=" yyyy-mm-dd"),
     *             @OA\Property(property="situacao",type="string",example="ativo",),
     *             @OA\Property(property="telefone_fixo",type="string",example="61-4002-6325"),
     *             @OA\Property(property="telefone_celular",type="string",example="61-94002-6325"),
     *             @OA\Property(property="aux_transporte",type="number",example="190,00"),
     *             @OA\Property(property="vale_alimentacao",type="number",example="560,00")
     *         ),
     *
     *          @OA\Schema(
     *             schema="Arquivos",
     *             type="object",
     *             @OA\Property(property="tipo",type="string",example="Contrato"),
     *             @OA\Property(property="processo",type="string",example="50600.000501/2020-04"),
     *             @OA\Property(property="sequencial_documento",type="integer",example="3"),
     *             @OA\Property(property="descricao",type="string",example="PUBLICA????O DOU TERMO ADITIVO"),
     *             @OA\Property(property="arquivos",type="array", @OA\Items(type="object", example="[{ 'arquivo_1': 'localhost:8000/storage/contrato/1_00420_2019/580e4da71ac02ec0ecf4f09728b51bc0.pdf'},{'arquivo_2': 'localhost:8000/storage/contrato/1_00420_2019/4e35d0c021543920a41402dfaa0ab89b.pdf'}]"))
     *       ),
     *          @OA\Schema(
     *             schema="Empenhos",
     *             type="object",
     *             @OA\Property(property="unidade_gestora",type="string",example="110099"),
     *             @OA\Property(property="gestao",type="string",example="00001"),
     *             @OA\Property(property="numero",type="string",example="2019NE800022"),
     *             @OA\Property(property="credor",type="string",example="09.439.320/0001-17 - GLOBAL SERVICOS & COMERCIO LTDA"),
     *             @OA\Property(property="planointerno",type="string",example="AGU0047 - SERVICOS DE PORTARIA"),
     *             @OA\Property(property="naturezadespesa",type="string",example="339039 - OUTROS SERVICOS DE TERCEIROS - PESSOA JURIDICA"),
     *             @OA\Property(property="empenhado",type="number",example="1.361.640,02"),
     *             @OA\Property(property="aliquidar",type="number",example="231.667,64"),
     *             @OA\Property(property="liquidado",type="number",example="0,00"),
     *             @OA\Property(property="pago",type="number",example="1.129.972,38"),
     *             @OA\Property(property="rpinscrito",type="number",example="231.667,64"),
     *             @OA\Property(property="rpaliquidar",type="number",example="128.941,72"),
     *             @OA\Property(property="rpliquidado",type="number",example="128.941,72"),
     *             @OA\Property(property="rppago",type="number",example="102.725,92"),
     *             @OA\Property(property="links",type="array", @OA\Items(type="object", example="{'documento_pagamento': 'http:\/\/sta.agu.gov.br\/api\/ordembancaria\/empenho\/110099000012017NE800559'}"))
     *       ),
     *          @OA\Schema(
     *             schema="Empenhos_id",
     *             type="object",
     *             @OA\Property(property="contrato_id",type="integer",example="3260"),
     *             @OA\Property(property="numero",type="string",example="2019NE800022"),
     *             @OA\Property(property="credor",type="string",example="09.439.320/0001-17 - GLOBAL SERVICOS & COMERCIO LTDA"),
     *             @OA\Property(property="planointerno",type="string",example="AGU0047 - SERVICOS DE PORTARIA"),
     *             @OA\Property(property="naturezadespesa",type="string",example="339039 - OUTROS SERVICOS DE TERCEIROS - PESSOA JURIDICA"),
     *             @OA\Property(property="empenhado",type="number",example="1.361.640,02"),
     *             @OA\Property(property="aliquidar",type="number",example="231.667,64"),
     *             @OA\Property(property="liquidado",type="number",example="0,00"),
     *             @OA\Property(property="pago",type="number",example="1.129.972,38"),
     *             @OA\Property(property="rpinscrito",type="number",example="231.667,64"),
     *             @OA\Property(property="rpaliquidar",type="number",example="128.941,72"),
     *             @OA\Property(property="rpliquidado",type="number",example="128.941,72"),
     *             @OA\Property(property="rppago",type="number",example="102.725,92"),
     *       ),
     *          @OA\Schema(
     *             schema="Cronograma",
     *             type="object",
     *             @OA\Property(property="tipo",type="string",example="Contrato"),
     *             @OA\Property(property="numero",type="string",example="00006/2017"),
     *             @OA\Property(property="receita_despesa",type="string",example="Despesa"),
     *             @OA\Property(property="observacao",type="string",example="CELEBRA????O DO CONTRATO: 0006/2017 DE ACORDO COM PROCESSO N??MERO: 00589.000328/2016-38"),
     *             @OA\Property(property="mesref",type="integer",example="04"),
     *             @OA\Property(property="anoref",type="integer",example="2017"),
     *             @OA\Property(property="vencimento",type="string",example="2017-05-01",format="yyyy-mm-dd"),
     *             @OA\Property(property="retroativo",type="string",example="N??o"),
     *             @OA\Property(property="valor",type="number",example="1.966.974,40"),
     *       ),
     *          @OA\Schema(
     *             schema="Orgao_id",
     *             type="object",
     *             @OA\Property(property="codigo",type="integer",example="14000"),
     *       ),
     *          @OA\Schema(
     *             schema="Unidade",
     *             type="object",
     *             @OA\Property(property="codigo",type="string",example="070001"),
     *       ),
     *     )
     *
     */

}
