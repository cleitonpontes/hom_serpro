<?php

namespace App\Http\Controllers\Api;

use App\Models\Contrato;
use App\Models\Contratoempenho;
use App\Models\Contratohistorico;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContratoController extends Controller
{
    public function empenhosPorContratoId(int $contrato_id)
    {
        $empenhos_array = [];
        $empenhos = $this->buscaEmpenhosPorContratoId($contrato_id);

        return json_encode($empenhos_array);
    }

    public function historicoPorContratoId(int $contrato_id)
    {
        $historico_array = [];
        $historicos = $this->buscaHistoricoPorContratoId($contrato_id);

        foreach ($historicos as $historico){
            $historico_array[] = [
                'receita_despesa' => ($historico->receita_despesa)=='D' ? 'Despesa' : 'Receita',
                'numero' => $historico->numero,
                'observacao' => $historico->observacao,
                'ug' => $historico->unidade->codigo,
                'fornecedor' => [
                    'tipo' => $historico->fornecedor->tipo_fornecedor,
                    'cnpj_cpf_idgener' => $historico->fornecedor->cpf_cnpj_idgener,
                    'nome' => $historico->fornecedor->nome,
                ],
                'tipo' => $historico->tipo->descricao ?? '',
                'categoria' => $historico->categoria->descricao ?? '',
                'processo' => $historico->processo,
                'objeto' => $historico->objeto,
                'informacao_complementar' => $historico->info_complementar,
                'modalidade' => $historico->modalidade->descricao ?? '',
                'licitacao_numero' => $historico->licitacao_numero,
                'data_assinatura' => $historico->data_assinatura,
                'data_publicacao' => $historico->data_publicacao,
                'vigencia_inicio' => $historico->vigencia_inicio,
                'vigencia_fim' => $historico->vigencia_fim,
                'valor_inicial' => number_format($historico->valor_inicial, 2, ',', '.'),
                'valor_global' => number_format($historico->valor_global, 2, ',', '.'),
                'num_parcelas' => $historico->num_parcelas,
                'valor_parcela' => number_format($historico->valor_parcela, 2, ',', '.'),
                'novo_valor_global' => number_format($historico->novo_valor_global, 2, ',', '.'),
                'novo_num_parcelas' => $historico->novo_num_parcelas,
                'novo_valor_parcela' => number_format($historico->novo_valor_parcela, 2, ',', '.'),
                'data_inicio_novo_valor' => $historico->data_inicio_novo_valor,
                'retroativo' => ($historico->retroativo)==true ? 'Sim' : 'Não',
                'retroativo_mesref_de' => $historico->retroativo_mesref_de,
                'retroativo_anoref_de' => $historico->retroativo_anoref_de,
                'retroativo_mesref_ate' => $historico->retroativo_mesref_ate,
                'retroativo_anoref_ate' => $historico->retroativo_anoref_ate,
                'retroativo_vencimento' => $historico->retroativo_vencimento,
                'retroativo_valor' => number_format($historico->retroativo_valor, 2, ',', '.'),

            ];

        }


        return json_encode($historico_array);
    }

    private function buscaEmpenhosPorContratoId(int $contrato_id)
    {
        $empenhos = Contratoempenho::where('contrato_id',$contrato_id)
            ->get();

        return $empenhos;
    }

    private function buscaHistoricoPorContratoId(int $contrato_id)
    {
        $historico = Contratohistorico::where('contrato_id',$contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        return $historico;
    }

    public function contratoAtivoPorUg(int $unidade)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosPorUg($unidade);

        foreach ($contratos as $contrato) {
            $contratos_array[] = [
                'receita_despesa' => ($contrato->receita_despesa)=='D' ? 'Despesa' : 'Receita',
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
                    'tipo' => $contrato->fornecedor->tipo_fornecedor,
                    'cnpj_cpf_idgener' => $contrato->fornecedor->cpf_cnpj_idgener,
                    'nome' => $contrato->fornecedor->nome,
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

}


