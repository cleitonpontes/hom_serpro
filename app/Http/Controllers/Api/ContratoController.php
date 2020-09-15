<?php

namespace App\Http\Controllers\Api;

use App\Models\Orgao;
use function foo\func;
use App\Models\Empenho;
use App\Models\Unidade;
use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\Contratoitem;
use Illuminate\Http\Request;
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

class ContratoController extends Controller
{
    public function orgaosComContratosAtivos()
    {
        return json_encode($this->buscaOrgaosComContratosAtivos());
    }

    public function unidadesComContratosAtivos()
    {
        return json_encode($this->buscaUnidadesComContratosAtivos());
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

    public function garantiasPorContratoId(int $contrato_id)
    {
        $garantias_array = [];
        $garantias = $this->buscaGarantiasPorContratoId($contrato_id);

        foreach ($garantias as $garantia) {

            $garantias_array[] = [
                //'contrato_id' => $garantia->contrato_id,
                'tipo' => $garantia->getTipo(),
                'valor' => number_format($garantia->valor, 2, ',', '.'),
                'vencimento' => $garantia->vencimento,
             ];

        }

        return json_encode($garantias_array);
    }

    //feito as cegas (faltou CATMATSER)
    public function itensPorContratoId(int $contrato_id)
    {
        $itens_array = [];
        $itens = $this->buscaItensPorContratoId($contrato_id);

        foreach ($itens as $item) {
            $itens_array[] = [
                //'contrato_id' => $item->contrato_id,
                'tipo_id' => $item->getTipo(),
                'grupo_id' => $item->getCatmatsergrupo(),
                'catmatseritem_id' => $item->getCatmatseritem(),
                'descricao_complementar' => $item->descricao_complementar,
                'quantidade' => $item->quantidade,
                'valorunitario' => number_format($item->valorunitario, 2, ',', '.'),
                'valortotal' => number_format($item->valortotal, 2, ',', '.'),
             ];

        }

        return json_encode($itens_array);
    }

    public function prepostosPorContratoId(int $contrato_id)
    {
        $prepostos_array = [];
        $prepostos = $this->buscaPrepostosPorContratoId($contrato_id);

        foreach ($prepostos as $preposto) {
            $prepostos_array[] = [
                //'contrato_id' => $preposto->contrato_id,
                //'user_id' => $preposto->user_id,
                //'cpf' => $preposto->getCpf(),
                //'nome' => $preposto->nome,
                'usuario' => $this->usuarioTransparencia($preposto->nome, $preposto->cpf),
                'email' => $preposto->email,
                'telefonefixo' => $preposto->telefonefixo,
                'celular' => $preposto->celular,
                'doc_formalizacao' => $preposto->doc_formalizacao,
                'informacao_complementar' => $preposto->informacao_complementar,
                'data_inicio' => $preposto->data_inicio,
                'data_fim' => $preposto->data_fim,
                'situacao' => $preposto->situacao == true ? 'Ativo' : 'Inativo',
             ];

        }

        return json_encode($prepostos_array);
    }

    public function responsaveisPorContratoId(int $contrato_id)
    {
        $responsaveis_array = [];
        $responsaveis = $this->buscaResponsaveisPorContratoId($contrato_id);

        foreach ($responsaveis as $responsavel) {

            $responsaveis_array[] = [

                //'contrato_id' => $responsavel->contrato_id,
                //'user_id' => $responsavel->getUsuarioTransparencia(),

                'usuario' => $this->usuarioTransparencia($responsavel->user->name, $responsavel->user->cpf),
                'funcao_id' => $responsavel->funcao->descricao,
                'instalacao_id' => $responsavel->getInstalacao(),
                'portaria' => $responsavel->portaria,
                'situacao' => $responsavel->situacao == true ? 'Ativo' : 'Inativo',
                'data_inicio' => $responsavel->data_inicio,
                'data_fim' => $responsavel->data_fim,
                'telefone_fixo' => $responsavel->telefone_fixo,
                'telefone_celular' => $responsavel->telefone_celular,

            ];

        }

        return json_encode($responsaveis_array);
    }

    public function despesasAcessoriasPorContratoId(int $contrato_id)
    {
        $despesasAcessorias_array = [];
        $despesasAcessorias = $this->buscaDespesasAcessoriasPorContratoId($contrato_id);

        foreach ($despesasAcessorias as $despesaAcessoria) {
            $despesasAcessorias_array[] = [
                //'contrato_id' => $despesaAcessoria->contrato_id,
                'tipo_id' => $despesaAcessoria->tipoDespesa->descricao,
                'recorrencia_id' => $despesaAcessoria->recorrenciaDespesa->descricao,
                'descricao_complementar' => $despesaAcessoria->descricao_complementar,
                'vencimento' => $despesaAcessoria->vencimento,
                'valor' => number_format($despesaAcessoria->valor, 2, ',', '.'),
             ];

        }

        return json_encode($despesasAcessorias_array);
    }

    public function faturasPorContratoId(int $contrato_id)
    {
        $faturas_array = [];
        $faturas = $this->buscaFaturasPorContratoId($contrato_id);

        foreach ($faturas as $fatura) {
            $faturas_array[] = [
                //'contrato_id' => $fatura->contrato_id,
                'tipolistafatura_id' => $fatura->tipolista->nome,
                //sem dados para teste
                'justificativafatura_id' => $fatura->getJustificativaFatura(),
                //sem dados para teste
                'sfadrao_id' => $fatura->getSfpadrao(),
                'numero' => $fatura->numero,
                'emissao' => $fatura->emissao,
                'prazo' => $fatura->prazo,
                'vencimento' => $fatura->vencimento,
                'valor' => number_format($fatura->valor, 2, ',', '.'),
                'juros' => number_format($fatura->juros, 2, ',', '.'),
                'multa' => number_format($fatura->multa, 2, ',', '.'),
                'glosa' => number_format($fatura->glosa, 2, ',', '.'),
                'valorliquido' => number_format($fatura->valorliquido, 2, ',', '.'),
                'processo' => $fatura->processo,
                'protocolo' => $fatura->protocolo,
                'ateste' => $fatura->ateste,
                'repactuacao' => $fatura->repactuacao == true ? 'Sim' : 'Não',
                'infcomplementar' => $fatura->infcomplementar,
                'mesref' => $fatura->mesref,
                'anoref' => $fatura->anoref,
                'situacao' => $fatura->retornaSituacao(),
            ];

        }

        return json_encode($faturas_array);
    }

    public function ocorrenciasPorContratoId(int $contrato_id)
    {
        $ocorrencias_array = [];
        $ocorrencias = $this->buscaOcorrenciasPorContratoId($contrato_id);

        foreach ($ocorrencias as $ocorrencia) {
            $ocorrencias_array[] = [
                'numero' => $ocorrencia->numero ,
                //'contrato_id' => $ocorrencia->contrato_id,
                //'user_id' => $ocorrencia->getUsuarioTransparencia(),
                'usuario' => $this->usuarioTransparencia($ocorrencia->usuario->name, $ocorrencia->usuario->cpf),
                'data' => $ocorrencia->data,
                'ocorrencia' => $ocorrencia->ocorrencia,
                'notificapreposto' => $ocorrencia->notificapreposto == true ? 'Sim' : 'Não',
                'emailpreposto' => $ocorrencia->emailpreposto,
                //Seria o mesmo que número?
                'numeroocorrencia' => $ocorrencia->getNumeroOcorrencia(),
                //possivel erro no formulário, nova situação não é salva
                'novasituacao' => $ocorrencia->getSituacaoNovaConsulta(),
                'situacao' => $ocorrencia->ocorSituacao->descricao,
                'arquivos' => $ocorrencia->arquivos,
             ];

        }

        return json_encode($ocorrencias_array);
    }


    /**
     * @OA\Get(
     *     tags={"contratos"},
     *     operationId="contrato",
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
     *         @OA\JsonContent(
     *              @OA\Items(ref="#/components/schemas/Terceirizados")
     *         ),
     *     ),
     *     @OA\Components(
     *         @OA\Schema(
     *             schema="Terceirizados",
     *             type="object",
     *             @OA\Property(properties="id",type="integer",format="int64"),
     *             @OA\Property(properties="name",type="string")
     *         )
     *     )
     * )
     */
    public function terceirizadosPorContratoId(int $contrato_id)
    {



        $terceirizados_array = [];
        $terceirizados = $this->buscaTerceirizadosPorContratoId($contrato_id);

        foreach ($terceirizados as $terceirizado) {
            $terceirizados_array[] = [
                //'contrato_id' => $terceirizado->contrato_id,
                //'cpf' => $terceirizado->getCpf(),
                //'nome' => $terceirizado->nome,
                'usuario' => $this->usuarioTransparencia($terceirizado->nome, $terceirizado->cpf),
                'funcao_id' => $terceirizado->funcao->descricao,
                'descricao_complementar' => $terceirizado->descricao_complementar,
                'jornada' => $terceirizado->jornada,
                'unidade' => $terceirizado->unidade,
                'salario' =>  number_format($terceirizado->salario, 2, ',', '.'),
                'custo' => number_format($terceirizado->custo, 2, ',', '.'),
                'escolaridade_id' => $terceirizado->escolaridade->descricao,
                'data_inicio' => $terceirizado->data_inicio,
                'data_fim' => $terceirizado->data_fim,
                'situacao' => $terceirizado->situacao == true ? 'Ativo' : 'Inativo',
                'telefone_fixo' => $terceirizado->telefone_fixo,
                'telefone_celular' => $terceirizado->telefone_celular,
                'aux_transporte' => number_format($terceirizado->aux_transporte, 2, ',', '.'),
                'vale_alimentacao' => number_format($terceirizado->vale_alimentacao, 2, ',', '.'),
             ];

        }

        return json_encode($terceirizados_array);
    }

    public function arquivosPorContratoId(int $contrato_id)
    {
        $arquivos_array = [];
        $arquivos = $this->buscaArquivosPorContratoId($contrato_id);

        foreach ($arquivos as $arquivo) {
            $arquivos_array[] = [
                //'contrato_id' => $arquivo->contrato_id,
                'tipo' => $arquivo->getTipo(),
                'processo' => $arquivo->processo,
                'sequencial_documento' => $arquivo->sequencial_documento,
                'descricao' => $arquivo->descricao,
                'arquivos' => $arquivo->getListaArquivosComPath(),
            ];

        }

        return json_encode($arquivos_array);
    }

    private function buscaOrgaosComContratosAtivos()
    {
        $orgaos = Orgao::select('codigo')
            ->whereHas('unidades', function ($u) {
                $u->whereHas('contratos', function ($c) {
                    $c->where('situacao', true);
                });
            })
            ->orderBy('codigo');

        return $orgaos->get();
    }

    private function buscaUnidadesComContratosAtivos()
    {
        $unidades = Unidade::select('codigo')
            ->whereHas('contratos', function ($c) {
                $c->where('situacao', true);
            })
            ->orderBy('codigo');

        return $unidades->get();
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

    private function buscaGarantiasPorContratoId(int $contrato_id)
    {
        $garantias = Contratogarantia::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $garantias;
    }

    private function buscaItensPorContratoId(int $contrato_id)
    {
        $itens = Contratoitem::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $itens;
    }

    private function buscaPrepostosPorContratoId(int $contrato_id)
    {
        $prepostos = Contratopreposto::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $prepostos;
    }

    private function buscaResponsaveisPorContratoId(int $contrato_id)
    {
        $responsaveis = Contratoresponsavel::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $responsaveis;
    }

    private function buscaDespesasAcessoriasPorContratoId(int $contrato_id)
    {
        $despesas_acessorias = Contratodespesaacessoria::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $despesas_acessorias;
    }

    private function buscaFaturasPorContratoId(int $contrato_id)
    {
        $faturas = Contratofatura::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $faturas;
    }

    private function buscaOcorrenciasPorContratoId(int $contrato_id)
    {
        $ocorrencias = Contratoocorrencia::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $ocorrencias;
    }

    private function buscaTerceirizadosPorContratoId(int $contrato_id)
    {
        $terceirizados = Contratoterceirizado::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $terceirizados;
    }

    private function buscaArquivosPorContratoId(int $contrato_id)
    {
        $arquivos = Contratoarquivo::where('contrato_id', $contrato_id)
            //->orderBy('data_assinatura')
            ->get();

        return $arquivos;
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
                'link_garantias' => url('/api/contrato/' . $contrato->id . '/garantias/'),
                'link_itens' => url('/api/contrato/' . $contrato->id . '/itens/'),
                'link_prepostos' => url('/api/contrato/' . $contrato->id . '/prepostos/'),
                'link_responsaveis' => url('/api/contrato/' . $contrato->id . '/responsaveis/'),
                'link_despesas_acessorias' => url('/api/contrato/' . $contrato->id . '/despesas_acessorias/'),
                'link_faturas' => url('/api/contrato/' . $contrato->id . '/faturas/'),
                'link_ocorrencias' => url('/api/contrato/' . $contrato->id . '/ocorrencias/'),
                'link_terceirizados' => url('/api/contrato/' . $contrato->id . '/terceirizados/'),
                'link_arquivos' => url('/api/contrato/' . $contrato->id . '/arquivos/'),

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
                    'garantias' => url('/api/contrato/' . $contrato->id . '/garantias/'),
                    'itens' => url('/api/contrato/' . $contrato->id . '/itens/'),
                    'prepostos' => url('/api/contrato/' . $contrato->id . '/prepostos/'),
                    'responsaveis' => url('/api/contrato/' . $contrato->id . '/responsaveis/'),
                    'despesas_acessorias' => url('/api/contrato/' . $contrato->id . '/despesas_acessorias/'),
                    'faturas' => url('/api/contrato/' . $contrato->id . '/faturas/'),
                    'ocorrencias' => url('/api/contrato/' . $contrato->id . '/ocorrencias/'),
                    'terceirizados' => url('/api/contrato/' . $contrato->id . '/terceirizados/'),
                    'arquivos' => url('/api/contrato/' . $contrato->id . '/arquivos/'),
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
                    'garantias' => url('/api/contrato/' . $contrato->id . '/garantias/'),
                    'itens' => url('/api/contrato/' . $contrato->id . '/itens/'),
                    'prepostos' => url('/api/contrato/' . $contrato->id . '/prepostos/'),
                    'responsaveis' => url('/api/contrato/' . $contrato->id . '/responsaveis/'),
                    'despesas_acessorias' => url('/api/contrato/' . $contrato->id . '/despesas_acessorias/'),
                    'faturas' => url('/api/contrato/' . $contrato->id . '/faturas/'),
                    'ocorrencias' => url('/api/contrato/' . $contrato->id . '/ocorrencias/'),
                    'terceirizados' => url('/api/contrato/' . $contrato->id . '/terceirizados/'),
                    'arquivos' => url('/api/contrato/' . $contrato->id . '/arquivos/'),
                ]
            ];

        }


        return json_encode($contratos_array);

    }

    public function contratoInativoPorUg(int $unidade)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosInativosPorUg($unidade);

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
                    'garantias' => url('/api/contrato/' . $contrato->id . '/garantias/'),
                    'itens' => url('/api/contrato/' . $contrato->id . '/itens/'),
                    'prepostos' => url('/api/contrato/' . $contrato->id . '/prepostos/'),
                    'responsaveis' => url('/api/contrato/' . $contrato->id . '/responsaveis/'),
                    'despesas_acessorias' => url('/api/contrato/' . $contrato->id . '/despesas_acessorias/'),
                    'faturas' => url('/api/contrato/' . $contrato->id . '/faturas/'),
                    'ocorrencias' => url('/api/contrato/' . $contrato->id . '/ocorrencias/'),
                    'terceirizados' => url('/api/contrato/' . $contrato->id . '/terceirizados/'),
                    'arquivos' => url('/api/contrato/' . $contrato->id . '/arquivos/'),
                ]
            ];

        }


        return json_encode($contratos_array);

    }

    public function contratoInativoPorOrgao(int $orgao)
    {
        $contratos_array = [];
        $contratos = $this->buscaContratosInativosPorOrgao($orgao);

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
                    'garantias' => url('/api/contrato/' . $contrato->id . '/garantias/'),
                    'itens' => url('/api/contrato/' . $contrato->id . '/itens/'),
                    'prepostos' => url('/api/contrato/' . $contrato->id . '/prepostos/'),
                    'responsaveis' => url('/api/contrato/' . $contrato->id . '/responsaveis/'),
                    'despesas_acessorias' => url('/api/contrato/' . $contrato->id . '/despesas_acessorias/'),
                    'faturas' => url('/api/contrato/' . $contrato->id . '/faturas/'),
                    'ocorrencias' => url('/api/contrato/' . $contrato->id . '/ocorrencias/'),
                    'terceirizados' => url('/api/contrato/' . $contrato->id . '/terceirizados/'),
                    'arquivos' => url('/api/contrato/' . $contrato->id . '/arquivos/'),
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

    private function buscaContratosInativosPorUg(string $unidade)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($unidade) {
            $q->whereHas('orgao', function ($o) {
                $o->where('situacao', true);
            })
                ->where('codigo', $unidade)
                ->where('situacao', true);
        })
            ->where('situacao', false)
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

    private function buscaContratosInativosPorOrgao(string $orgao)
    {
        $contratos = Contrato::whereHas('unidade', function ($q) use ($orgao) {
            $q->whereHas('orgao', function ($o) use ($orgao) {
                $o->where('codigo', $orgao)
                    ->where('situacao', true);
            });
        })
            ->where('situacao', false)
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

    private function usuarioTransparencia(string $nome, string $cpf)
    {
        $cpf = '***' . substr($cpf,3,9) . '**';

        return $cpf . ' - ' . $nome;
    }

}


