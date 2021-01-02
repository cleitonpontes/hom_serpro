<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\Formatador;
use App\Models\Codigoitem;
use App\Models\Contrato;
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

        $dados['uasg'] = @str_pad($request->uasg, 6, "0", STR_PAD_LEFT);
        $dados['modalidade'] = @str_pad($request->modalidade, 2, "0", STR_PAD_LEFT);
        $dados['numeroAno'] = @str_pad($request->numero, 5, "0", STR_PAD_LEFT) . '/' . @$request->ano;
        $dados['itens'] = $this->trataItens($request->itens);

        $unidade = $this->buscaUnidadePorCodigo($dados['uasg']);
        $modalidade = $this->buscaModalidadePorCodigo($dados['modalidade']);

        if (isset($unidade->id) and isset($modalidade->id)) {

            $retorno['itens'] = [];

            foreach ($dados['itens'] as $item) {
                $num_item = str_pad($item, 5, "0", STR_PAD_LEFT);

                $contratos = $this->buscaContratosItemUnidadeCompra($num_item, $modalidade->id, $unidade->id, $dados['numeroAno']);

                $array_contratos = [];
                foreach ($contratos as $contrato) {
                    $unidadeorigem = $contrato->unidadeorigem->codigo;
                    $unidadeatual = $contrato->unidade->codigo;
                    $unidadesubrrogacao = ($unidadeorigem == $unidadeatual) ? '000000' : $unidadeatual;
                    $tipo = $contrato->tipo->descres;
                    $numero_contrato = str_replace('/', '', $contrato->numero);

                    $array_contratos[] .= $unidadeorigem . $tipo . $numero_contrato . $unidadesubrrogacao;
                }

                $array_empenhos = [];


                $retorno['itens'][] = [
                    'nroItem' => $num_item,
                    'contratosAtivos' => $array_contratos,
                    'empenhos' => $array_empenhos
                ];

            }

//            $retorno = [
//                'itens' => [
//                    [
//                        'nroItem' => '0001',
//                        'contratosAtivos' => [
//                            '11016150000012019000000',
//                            '11062150000022019000000',
//                        ],
//                        'empenhos' => [
//                            '110161000012020NE000001',
//                            '110621000012020NE000001',
//                        ],
//                    ],
//                    [
//                        'nroItem' => '0002',
//                        'contratosAtivos' => [
//
//                        ],
//                        'empenhos' => [
//
//                        ],
//                    ],
//                ]
//            ];
        }


        return $retorno;
    }

    private function buscaContratosItemUnidadeCompra(string $item, int $modalidade, int $unidade, string $numeroAnoCompra)
    {
        $contratos = Contrato::whereHas('itens', function ($i) use ($item) {
            $i->where('numero_item_compra', $item)
                ->where('valortotal', '>', 0);
        })
            ->where('modalidade_id', $modalidade)
            ->where('unidadecompra_id', $unidade)
            ->where('licitacao_numero', $numeroAnoCompra);

        return $contratos->get();

    }

    private function trataItens(string $itens)
    {
        $array = [];

        if ($itens) {
            $itens = str_replace(']', '', str_replace('[', '', $itens));

            if (strpos($itens, ',') !== false) {
                $array = explode(',', $itens);
            } else {
                $array[] = $itens;
            }
        }

        return $array;
    }

    public function getDadosContratosPorItem(Request $request)
    {
        $retorno = [];

        if (empty($request->uasgCompra) or empty($request->modalidade) or empty($request->numeroCompra) or empty($request->anoCompra) or empty($request->numeroItem)) {
            return $retorno;
        }

        //obrigatorios
        $dados['uasgCompra'] = str_pad($request->uasgCompra, 6, "0", STR_PAD_LEFT);
        $dados['modalidade'] = str_pad($request->modalidade, 2, "0", STR_PAD_LEFT);
        $dados['numeroAnoCompra'] = str_pad($request->numeroCompra, 5, "0", STR_PAD_LEFT) . '/' . $request->anoCompra;
        $dados['item_compra'] = str_pad($request->numeroItem, 5, "0", STR_PAD_LEFT);

        //opcionais
        $dados['uasg_contrato'] = @str_pad($request->uasgContrato, 6, "0", STR_PAD_LEFT);
        $dados['fornecedor'] = @$request->fornecedor;

        $unidade_compra = ($dados['uasgCompra']) ? $this->buscaUnidadePorCodigo($dados['uasgCompra']) : null;
        $modalidade = ($dados['modalidade']) ? $this->buscaModalidadePorCodigo($dados['modalidade']) : null;
        $unidade_contrato = ($dados['uasg_contrato']) ? $this->buscaUnidadePorCodigo($dados['uasg_contrato']) : null;


        if (isset($unidade_compra->id) and isset($modalidade->id)) {
            $dados = Contratoitem::whereHas('contrato', function ($q) use ($dados, $unidade_compra, $modalidade, $unidade_contrato) {
                $q->where('unidadecompra_id', $unidade_compra->id)
                    ->where('modalidade_id', $modalidade->id)
                    ->where('licitacao_numero', $dados['numeroAnoCompra']);
                if (isset($unidade_contrato->id)) {
                    $q->where('unidade_id', $unidade_contrato->id);
                }
                if ($dados['fornecedor']) {
                    $q->whereHas('fornecedor', function ($f) use ($dados) {
                        $f->where('cpf_cnpj_idgener', $this->formataCnpjCpf($dados['fornecedor']));
                    });
                }
            })
                ->where('numero_item_compra', $dados['item_compra'])
                ->get();

            foreach ($dados as $dado) {
                $instrumento_inicial = $dado->contrato->historico()->whereHas('tipo', function ($t) {
                    $t->where('descricao', '<>', 'Termo Aditivo')
                        ->where('descricao', '<>', 'Termo de Apostilamento')
                        ->where('descricao', '<>', 'Termo de Rescisão');
                })->first();

                $ultimo_historico = $dado->contrato->historico()->orderBy('data_assinatura', 'DESC')->first();
                $publicacao = $ultimo_historico->publicacao()->latest()->first();


                if ($ultimo_historico->tipo->descricao == 'Termo de Rescisão') {
                    $situacao_publicacao = $publicacao->StatusPublicacaoDescress;
                    if($publicacao->StatusPublicacaoDescres == '02'){
                        $situacao_publicacao = '08';
                    }
                    if($publicacao->StatusPublicacaoDescres == '05'){
                        $situacao_publicacao = '09';
                    }
                }else{
                    $situacao_publicacao = $publicacao->StatusPublicacaoDescres;
                }

                $unidade_atual = ($dado->contrato->unidade->codigo == $dado->contrato->unidadeorigem->codigo) ? null : $dado->contrato->unidade->codigo;

                $retorno[] = [
                    'unidade_origem' => @$dado->contrato->unidadeorigem->codigo,
                    'unidade_atual' => @$unidade_atual,
                    'numero_contrato' => @$dado->contrato->numero,
                    'tipo' => @$dado->contrato->tipo->descres,
                    'fornecedor' => @$dado->contrato->fornecedor->cpf_cnpj_idgener,
                    'vigencia_fim_inicial' => @$instrumento_inicial->vigencia_fim,
                    'vigencia_fim' => @$dado->contrato->vigencia_fim,
                    'quantidade_item' => @number_format($dado->quantidade, 0, '', ''),
                    'valor_unitario_item' => @$dado->valorunitario,
                    'valor_total_item' => @$dado->valortotal,
                    'situacao_publicacao' => $situacao_publicacao,
                    /*
                     *  todo implementar esse retorno.
                     *  01 - TRANSFERIDO PARA IMPRENSA
                     *  02 - PUBLICADO
                     *  03 - INFORMADO
                     *  05 - A PUBLICAR
                     *  07 - DEVOLVIDO PELA IMPRENSA
                     *  08 - EVENTO DE RESCISÃO PUBLICADO
                     *  09 - EVENTO DE RESCISÃO A PUBLICAR
                     */
                ];
            }
        }

        return $retorno;
    }

    private function buscaUltimaPublicacao(int $contratohistorico_id)
    {

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
