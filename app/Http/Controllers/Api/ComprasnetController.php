<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\Formatador;
use App\Models\Codigoitem;
use App\Models\Contratoitem;
use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComprasnetController extends Controller
{
    use Formatador;

    public function getContratosEmpenhosPorItens(Request $request)
    {
        $retorno = [];

        if (empty($request->uasg) or empty($request->modalidade) or empty($request->numero) or empty($request->ano) or empty($request->itens)) {
            return $retorno;
        }

        $dados['uasg'] = $request->uasg;
        $dados['modalidade'] = $request->modalidade;
        $dados['numero'] = $request->numero;
        $dados['ano'] = $request->ano;
        $dados['itens'] = $request->itens;

        $retorno = [
            'itens' => [
                [
                    'nroItem' => '0001',
                    'contratosAtivos' => [
                        '11016150000012019000000',
                        '11062150000022019000000',
                    ],
                    'empenhos' => [
                        '110161000012020NE000001',
                        '110621000012020NE000001',
                    ],
                ],
                [
                    'nroItem' => '0002',
                    'contratosAtivos' => [

                    ],
                    'empenhos' => [

                    ],
                ],
            ]
        ];

        return $retorno;
    }

    public function getDadosContratosPorItem(Request $request)
    {
        $retorno = [];

        if (empty($request->uasgCompra) or empty($request->modalidade) or empty($request->numeroCompra) or empty($request->numeroCompra) or empty($request->numeroItem)) {
            return $retorno;
        }

        //obrigatorios
        $dados['uasgCompra'] = $request->uasgCompra;
        $dados['modalidade'] = $request->modalidade;
        $dados['numeroAnoCompra'] = $request->numeroCompra . '/' . $request->anoCompra;
        $dados['item_compra'] = str_pad($request->numeroItem, 5, "0", STR_PAD_LEFT);

        //opcionais
        $dados['uasg_contrato'] = @$request->uasgContrato;
        $dados['fornecedor'] = @$request->fornecedor;

        $unidade_compra = ($dados['uasgCompra']) ? $this->buscaUnidadePorCodigo($dados['uasgCompra']) : null;
        $modalidade = ($dados['modalidade']) ? $this->buscaModalidadePorCodigo($dados['modalidade']) : null;
        $unidade_contrato = ($dados['uasg_contrato']) ? $this->buscaUnidadePorCodigo($dados['uasg_contrato']) : null;

        if (isset($unidade_compra->id) and isset($modalidade->id)) {

            $dados = Contratoitem::whereHas('contrato', function ($q) use ($dados, $unidade_compra,$modalidade, $unidade_contrato){
                $q->where('unidadecompra_id',$unidade_compra->id)
                    ->where('modalidade_id',$modalidade->id)
                    ->where('licitacao_numero',$dados['numeroAnoCompra']);
                if(isset($unidade_contrato->id)){
                    $q->where('unidade_id',$unidade_contrato->id);
                }
                if($dados['fornecedor']){
                    $q->whereHas('fornecedor', function ($f) use($dados){
                        $f->where('cpf_cnpj_idgener',$this->formataCnpjCpf($dados['fornecedor']));
                    });
                }
            })
                ->where('numero_item_compra',$dados['item_compra'])
                ->get();


            foreach ($dados as $dado){
                $instrumento_inicial = $dado->contrato->historico()->whereHas('tipo', function ($t){
                    $t->where('descricao', '<>', 'Termo Aditivo')
                        ->where('descricao', '<>', 'Termo de Apostilamento')
                        ->where('descricao', '<>', 'Termo de Rescisão');
                })->first();

                $retorno[] = [
                    'unidade_origem' => @$dado->contrato->unidadeorigem->codigo,
                    'unidade_atual' => @$dado->contrato->unidade->codigo,
                    'numero_contrato' => @$dado->contrato->numero,
                    'tipo' => @$dado->contrato->tipo->descres,
                    'fornecedor' => @$dado->contrato->fornecedor->cpf_cnpj_idgener,
                    'vigencia_fim_inicial' => @$instrumento_inicial->vigencia_fim,
                    'vigencia_fim' => @$dado->contrato->vigencia_fim,
                    'quantidade_item' => @number_format($dado->quantidade,0,'',''),
                    'valor_unitario_item' => @$dado->valorunitario,
                    'valor_total_item' => @$dado->valortotal,
                    'situacao_publicacao' => 'PUBLICADO'
                ];
            }
        }

        return $retorno;
    }

    private function buscaUnidadePorCodigo(string $codigo)
    {
        $unidade = Unidade::where('codigo', $codigo)->first();
        return $unidade;
    }

    private function buscaModalidadePorCodigo(string $codigo)
    {
        $modalidade = Codigoitem::whereHas('codigo', function ($q) {
            $q->where('descricao', 'Modalidade Licitação');
        })
            ->where('descres', $codigo)
            ->first();

        return $modalidade;
    }

}
