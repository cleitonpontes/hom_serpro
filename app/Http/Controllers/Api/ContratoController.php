<?php

namespace App\Http\Controllers\Api;

use App\Models\Contrato;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContratoController extends Controller
{
    public function empenhosPorContratoId(int $contrato_id)
    {
        $empenhos_array = [];

        return json_encode($empenhos_array);
    }

    public function historicoPorContratoId(int $contrato_id)
    {
        $historico_array = [];

        return json_encode($historico_array);
    }

    public function contratoAtivoPorUg(int $unidade)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosPorUg($unidade);

        foreach ($contratos as $contrato) {
            $fornecedor = $this->buscaFornecedorPorId($contrato->fornecedor_id);

            $contratos_array[] = [
                'id' => $contrato->id,
                'numero' => $contrato->numero,
                'contratante' => [
                    'orgao' => [
                        'codigo' => $contrato->unidade->orgao->codigo,
                        'nome' => $contrato->unidade->orgao->nome,
                        'unidade_gestora' => [
                            'codigo' => $contrato->unidade->codigo,
                            'nome_resumido' => $contrato->unidade->nomeresumido,
                            'nome' => $contrato->unidade->nome,
                        ],
                    ],
                ],
                'fornecedor' => [
                    'tipo' => $fornecedor->tipo_fornecedor,
                    'cnpj_cpf_idgener' => $fornecedor->cpf_cnpj_idgener,
                    'nome' => $fornecedor->nome,
                ],
                'tipo' => $contrato->tipo->descricao,
                'categoria' => $contrato->categoria->descricao,
                'processo' => $contrato->processo,
                'objeto' => $contrato->objeto,
                'informacao_complementar' => $contrato->info_complementar,
                'modalidade' => $contrato->modalidade->descricao,
                'licitacao_numero' => $contrato->licitacao_numero,
                'data_assinatura' => $contrato->data_assinatura,
                'data_publicacao' => $contrato->data_publicacao,
                'vigencia_inicio' => $contrato->vigencia_inicio,
                'vigencia_fim' => $contrato->vigencia_fim,
                'valor_inicial' => number_format($contrato->valor_inicial, 2, ',', '.'),
                'valor_global' => number_format($contrato->valor_global, 2, ',', '.'),
                'num_parcelas' => $contrato->num_parcelas,
                'valor_parcela' => number_format($contrato->valor_parcela, 2, ',', '.'),
                'valor_acumulado' => number_format($contrato->valor_acumulado, 2, ',', '.'),
                'links' => [
                    'historico' => url('/api/contrato/' . $contrato->id . '/historico/'),
                    'empenhos' => url('/api/contrato/' . $contrato->id . '/empenhos/'),
                ]
            ];

        }


        return json_encode($contratos_array);

    }

    private function buscaContratosPorUg(string $unidade)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($unidade) {
            $q->where('codigo', $unidade);
        })
            ->where('situacao', true)
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaFornecedorPorId(int $fornecedor_id)
    {
        $fornecedor = Fornecedor::find($fornecedor_id);

        return $fornecedor;
    }
}


