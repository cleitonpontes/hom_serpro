<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComprasnetController extends Controller
{
    public function getContratosEmpenhosPorItens(Request $request)
    {
        $dados['uasg'] = $request->uasg;
        $dados['modalidade'] = $request->modalidade;
        $dados['numero'] = $request->numero;
        $dados['ano'] = $request->ano;
        $dados['itens'] = $request->itens;

        $retorno = [
            'item1' => [
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
            'item2' => [
                'nroItem' => '0002',
                'contratosAtivos' => [
                    'Nao possui contratos ativos'
                ],
                'empenhos' => [
                    'Nao ha empenho para esse item',
                ],
            ],
        ];

        return $retorno;
    }

    public function getDadosContratosPorItem(Request $request)
    {
        $retorno = [
            'unidade_origem' => '110161',
            'unidade_atual' => '110161',
            'numero_contrato' => '00001/2019',
            'tipo' => '50',
            'fornecedor' => '00.000.000/0001-91',
            'vigencia_fim_inicial' => '2019-12-31',
            'vigencia_fim' => '2020-12-31',
            'quantidade_item' => '3502',
            'valor_unitario_item' => '10.0500',
            'valor_total_item' => '35195.10',
            'situacao_publicacao' => 'PUBLICADO'
        ];

        return $retorno;
    }

}
