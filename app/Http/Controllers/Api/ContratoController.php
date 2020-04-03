<?php

namespace App\Http\Controllers\Api;

use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratoempenho;
use App\Models\Contratohistorico;
use App\Models\Empenho;
use App\Models\Fornecedor;
use App\Models\Orgao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class ContratoController extends Controller
{
    public function orgaosComContratosAtivos()
    {
        return json_encode($this->buscaOrgaosComContratosAtivos());
    }

    public function cronogramaPorContratoId(int $contrato_id)
    {
        $cronograma_array = [];
        $cronogramas = $this->buscaCronogramasPorContratoId($contrato_id);

        foreach ($cronogramas as $cronograma) {
            $cronograma_array[] = [
                'tipo' => $cronograma->contratohistorico->tipo->descricao,
                'numero' => $cronograma->contratohistorico->numero,
                'receita_despesa' => ($cronograma->receita_despesa) == 'D' ? 'Despesa' : 'Receita',
                'observacao' => $cronograma->contratohistorico->observacao,
                'mesref' => $cronograma->mesref,
                'anoref' => $cronograma->anoref,
                'vencimento' => $cronograma->vencimento,
                'retroativo' => ($cronograma->retroativo) == true ? 'Sim' : 'Não',
                'valor' => number_format($cronograma->valor, 2, ',', '.'),
            ];
        }

        return json_encode($cronograma_array);

    }

    public function empenhosPorContratos()
    {
        $empenhos_array = [];
        $emp = new Contratoempenho();
        $empenhos = $emp->buscaTodosEmpenhosContratosAtivos();

        foreach ($empenhos as $e) {
            $empenhos_array[] = [
                'contrato_id' => $e->contrato->id,
                'numero' => @$e->empenho->numero,
                'credor' => @$e->fornecedor->cpf_cnpj_idgener . ' - ' . @$e->fornecedor->nome ?? '',
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

    public function empenhosPorContratoId(int $contrato_id)
    {
        $empenhos_array = [];
        $empenhos = $this->buscaEmpenhosPorContratoId($contrato_id);

        foreach ($empenhos as $e) {
            $empenhos_array[] = [
                'numero' => @$e->empenho->numero,
                'credor' => @$e->fornecedor->cpf_cnpj_idgener . ' - ' . @$e->fornecedor->nome ?? '',
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

    public function historicoPorContratoId(int $contrato_id)
    {
        $historico_array = [];
        $historicos = $this->buscaHistoricoPorContratoId($contrato_id);

        foreach ($historicos as $historico) {
            $historico_array[] = [
                'receita_despesa' => ($historico->receita_despesa) == 'D' ? 'Despesa' : 'Receita',
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
                'retroativo' => ($historico->retroativo) == true ? 'Sim' : 'Não',
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

    private function buscaOrgaosComContratosAtivos()
    {
        $orgaos = Orgao::select('codigo')
            ->whereHas('unidades', function ($u) {
                $u->whereHas('contratos', function ($c){
                    $c->where('situacao',true);
                });
            })
            ->orderBy('codigo');

        return $orgaos->get();
    }

    private function buscaCronogramasPorContratoId(int $contrato_id)
    {
        $cronogramas = Contratocronograma::where('contrato_id', $contrato_id)
            ->get();

        return $cronogramas;
    }

    private function buscaEmpenhosPorContratoId(int $contrato_id)
    {
        $empenhos = Contratoempenho::where('contrato_id', $contrato_id)
            ->get();

        return $empenhos;
    }

    private function buscaHistoricoPorContratoId(int $contrato_id)
    {
        $historico = Contratohistorico::where('contrato_id', $contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        return $historico;
    }

    public function contratoAtivoAll()
    {
        $contratos_array = [];
        $contratos = $this->buscaContratos();

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
                'link_historico' => url('/api/contrato/' . $contrato->id . '/historico/'),
                'link_empenhos' => url('/api/contrato/' . $contrato->id . '/empenhos/'),
                'link_cronograma' => url('/api/contrato/' . $contrato->id . '/cronograma/'),

            ];

        }


        return json_encode($contratos_array);

    }

    public function contratoAtivoPorUg(int $unidade)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosPorUg($unidade);

        foreach ($contratos as $contrato) {
            $contratos_array[] = [
                'id' => $contrato->id,
                'receita_despesa' => ($contrato->receita_despesa) == 'D' ? 'Despesa' : 'Receita',
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
                'subcategoria' => @$contrato->orgaosubcategoria->descricao,
                'unidades_requisitantes' => $contrato->unidades_requisitantes,
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
                    'cronograma' => url('/api/contrato/' . $contrato->id . '/cronograma/'),
                ]
            ];

        }


        return json_encode($contratos_array);

    }

    public function contratoAtivoPorOrgao(int $orgao)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosPorOrgao($orgao);

        foreach ($contratos as $contrato) {
            $contratos_array[] = [
                'id' => $contrato->id,
                'receita_despesa' => ($contrato->receita_despesa) == 'D' ? 'Despesa' : 'Receita',
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
                'subcategoria' => @$contrato->orgaosubcategoria->descricao,
                'unidades_requisitantes' => $contrato->unidades_requisitantes,
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
                    'cronograma' => url('/api/contrato/' . $contrato->id . '/cronograma/'),
                ]
            ];

        }


        return json_encode($contratos_array);

    }

    private function buscaContratosPorUg(string $unidade)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($unidade) {
            $q->whereHas('orgao', function ($o) {
                $o->where('situacao', true);
            })
                ->where('codigo', $unidade)
                ->where('situacao', true);
        })
            ->where('situacao', true)
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaContratosPorOrgao(string $orgao)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($orgao) {
            $q->whereHas('orgao', function ($o) use ($orgao) {
                $o->where('codigo', $orgao)
                    ->where('situacao', true);
            });
        })
            ->where('situacao', true)
            ->orderBy('id')
            ->get();

        return $contratos;
    }

    private function buscaContratos()
    {
        $contratos = Contrato::whereHas('unidade', function ($q) {
            $q->whereHas('orgao', function ($o) {
                $o->where('situacao', true);
            });
        })
            ->where('situacao', true)
            ->orderBy('id')
            ->get();

        return $contratos;
    }

}


