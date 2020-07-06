<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contratohistorico;
use App\Models\Instalacao;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultaresponsavelCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Anderson Sathler <asathler@gmail.com>
 */
class ConsultahistoricoCrudController extends ConsultaContratoBaseCrudController
{
    /**
     * Configurações iniciais do Backpack
     *
     * @throws \Exception
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratohistorico');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/historicos');
        $this->crud->setEntityNameStrings('Histórico', 'Históricos');
        $this->crud->setHeading('Consulta Históricos por Contrato');

        $this->crud->addClause('leftJoin', 'contratos',
            'contratos.id', '=', 'contratohistorico.contrato_id'
        );
        $this->crud->addClause('leftJoin', 'fornecedores',
            'fornecedores.id', '=', 'contratos.fornecedor_id'
        );
        $this->crud->addClause('select', [
            'contratos.*',
            'fornecedores.*',
            // Tabela principal deve ser sempre a última da listagem!
            'contratohistorico.*'
        ]);

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'contratos.unidade_id', '=', session('user_ug_id'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->defineConfiguracaoPadrao();
    }

    /**
     * Action para exibição de um único registro
     *
     * @param int $id
     * @return \Backpack\CRUD\app\Http\Controllers\Operations\Response
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contrato_id',
            'fornecedor_id',
            'unidade_id',
            'tipo_id',
            'categoria_id',
            'subcategoria_id',
            'modalidade_id',
            'receita_despesa',
            'valor_inicial',
            'valor_global',
            'valor_parcela',
            'valor_acumulado',
            'novo_valor_global',
            'novo_num_parcelas',
            'novo_valor_parcela',
            'retroativo',
            'retroativo_vencimento',
            'retroativo_soma_subtrai',
            'retroativo_valor',
            'retroativo_mesref_de',
            'retroativo_mesref_ate',
            'retroativo_anoref_de',
            'retroativo_anoref_ate',
            'situacao'
        ]);

        return $content;
    }

    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem()
    {
        $this->adicionaColunaReceitaDespesa();
        $this->adicionaColunaTipo();
        $this->adicionaColunaObservacao();
        $this->adicionaColunaHistoricoNumeroContrato();
        $this->adicionaColunaUnidadesRequisitantes();
        $this->adicionaColunaCategoria();
        $this->adicionaColunaSubcategoria();
        $this->adicionaColunaHistoricoFornecedor();
        $this->adicionaColunaProcesso();
        $this->adicionaColunaHistoricoObjeto();
        $this->adicionaColunaHistoricoInformacoesComplementares();
        $this->adicionaColunaHistoricoFundamentoLegal();
        $this->adicionaColunaModalidade();
        $this->adicionaColunaLicitacao();
        $this->adicionaColunaHistoricoDataAssinatura();
        $this->adicionaColunaHistoricoDataPublicacao();
        $this->adicionaColunaHistoricoDataInicioNovoValor();
        $this->adicionaColunaHistoricoDataVigenciaInicio();
        $this->adicionaColunaHistoricoDataVigenciaFim();
        $this->adicionaColunaHistoricoValorGlobal();
        $this->adicionaColunaHistoricoNumeroParcelas();
        $this->adicionaColunaHistoricoValorParcela();
        $this->adicionaColunaHistoricoSituacaoSiasg();
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
        $this->aplicaFiltroReceitaDespesa();
        $this->aplicaFiltroTipo();
        $this->aplicaFiltroObservacao();
        $this->aplicaFiltroHistoricoNumeroContrato();
        $this->aplicaFiltroCategoria();
        $this->aplicaFiltroFundamentoLegal();
        $this->aplicaFiltroModalidade();
        $this->aplicaFiltroHistoricoValorGlobal();
    }

    /**
     * Adiciona o campo Receita ou Despesa na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaReceitaDespesa()
    {
        $this->crud->addColumn([
            'name' => 'getReceitaDespesaHistorico',
            'label' => 'Receita / Despesa',
            'type' => 'model_function',
            'function_name' => 'getReceitaDespesaHistorico',
            'priority' => 10,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Tipo na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaTipo()
    {
        $this->crud->addColumn([
            'name' => 'getTipo',
            'label' => 'Tipo',
            'type' => 'model_function',
            'function_name' => 'getTipo',
            'priority' => 11,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Observação na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaObservacao()
    {
        $this->crud->addColumn([
            'name' => 'observacao',
            'label' => 'Observação',
            'type' => 'text',
            'limit' => 1000,
            'priority' => 12,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Número do Contrato (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoNumeroContrato()
    {
        $this->crud->addColumn([
            'name' => 'numero',
            'label' => 'Núm. Contrato Hist.',
            'type' => 'text',
            'priority' => 13,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Unidades Requisitantes na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaUnidadesRequisitantes()
    {
        $this->crud->addColumn([
            'name' => 'unidades_requisitantes',
            'label' => 'Unidades Requisitantes',
            'type' => 'text',
            'priority' => 14,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Categoria na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaCategoria()
    {
        $this->crud->addColumn([
            'name' => 'getCategoria',
            'label' => 'Categoria',
            'type' => 'model_function',
            'function_name' => 'getCategoria',
            'priority' => 15,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Subategoria na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaSubcategoria()
    {
        $this->crud->addColumn([
            'name' => 'getSubCategoria',
            'label' => 'Subcategoria',
            'type' => 'model_function',
            'function_name' => 'getSubCategoria',
            'priority' => 16,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Fornecedor (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoFornecedor()
    {
        $this->crud->addColumn([
            'name' => 'getFornecedorHistorico',
            'label' => 'Fornecedor Hist.',
            'type' => 'model_function',
            'function_name' => 'getFornecedorHistorico',
            'limit' => 1000,
            'priority' => 17,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Processo na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaProcesso()
    {
        $this->crud->addColumn([
            'name' => 'processo',
            'label' => 'Processo',
            'type' => 'text',
            'priority' => 18,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Objeto (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoObjeto()
    {
        $this->crud->addColumn([
            'name' => 'objeto',
            'label' => 'Objeto',
            'type' => 'text',
            'limit' => 1000,
            'priority' => 19,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Informações Complementares (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoInformacoesComplementares()
    {
        $this->crud->addColumn([
            'name' => 'info_complementar',
            'label' => 'Informações Complementares',
            'type' => 'text',
            'priority' => 20,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Fundamento Legal (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoFundamentoLegal()
    {
        $this->crud->addColumn([
            'name' => 'fundamento_legal',
            'label' => 'Fundamento Legal',
            'type' => 'text',
            'priority' => 21,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Modalidade (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaModalidade()
    {
        $this->crud->addColumn([
            'name' => 'getModalidade',
            'label' => 'Modalidade',
            'type' => 'model_function',
            'function_name' => 'getModalidade',
            'limit' => 1000,
            'priority' => 22,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Licitação (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaLicitacao()
    {
        $this->crud->addColumn([
            'name' => 'licitacao_numero',
            'label' => 'Número Licitação',
            'type' => 'text',
            'priority' => 23,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Data de Assinatura (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoDataAssinatura()
    {
        $this->crud->addColumn([
            'name' => 'data_assinatura',
            'label' => 'Data Assinatura',
            'type' => 'date',
            'priority' => 24,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Data de Publicação (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoDataPublicacao()
    {
        $this->crud->addColumn([
            'name' => 'data_publicacao',
            'label' => 'Data Publicação',
            'type' => 'date',
            'priority' => 25,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Data de Início do Novo Valor (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoDataInicioNovoValor()
    {
        $this->crud->addColumn([
            'name' => 'data_inicio_novo_valor',
            'label' => 'Dt. Início Novo Valor',
            'type' => 'date',
            'priority' => 26,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Data de Início da Vigência (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoDataVigenciaInicio()
    {
        $this->crud->addColumn([
            'name' => 'vigencia_inicio',
            'label' => 'Vig. Início Hist.',
            'type' => 'date',
            'priority' => 27,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Data de Fim da Vigência (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoDataVigenciaFim()
    {
        $this->crud->addColumn([
            'name' => 'vigencia_fim',
            'label' => 'Vig. Fim Hist.',
            'type' => 'date',
            'priority' => 28,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Valor Global (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoValorGlobal()
    {
        $this->crud->addColumn([
            'name' => 'formatVlrGlobalHistorico',
            'label' => 'Valor Global Hist.',
            'type' => 'model_function',
            'function_name' => 'formatVlrGlobalHistorico',
            'priority' => 29,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Número de Parcelas (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoNumeroParcelas()
    {
        $this->crud->addColumn([
            'name' => 'num_parcelas',
            'label' => 'Núm. Parcelas Hist.',
            'type' => 'number',
            'priority' => 30,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Valor Parcela (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoValorParcela()
    {
        $this->crud->addColumn([
            'name' => 'formatVlrParcelaHistorico',
            'label' => 'Valor Parcela Hist.',
            'type' => 'model_function',
            'function_name' => 'formatVlrParcelaHistorico',
            'priority' => 31,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o campo Sitação SIASG (do Histórico) na listagem
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function adicionaColunaHistoricoSituacaoSiasg()
    {
        $this->crud->addColumn([
            'name' => 'situacao_siasg',
            'label' => 'Situação SIASG',
            'type' => 'text',
            'priority' => 32,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Adiciona o filtro ao campo Receita ou Despesa
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroReceitaDespesa()
    {
        $campo = [
            'name' => 'receitadespesa',
            'type' => 'select2',
            'label' => 'Receita / Despesa'
        ];

        $opcoes = ['D' => 'Despesa', 'R' => 'Receita'];

        $this->crud->addFilter(
            $campo,
            $opcoes,
            function ($value) {
                $this->crud->addClause('where', 'contratohistorico.receita_despesa', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Tipo
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroTipo()
    {
        $campo = [
            'name' => 'tipo',
            'type' => 'select2',
            'label' => 'Tipo'
        ];

        $tipos = $this->retornaTiposParaCombo();

        $this->crud->addFilter(
            $campo,
            $tipos,
            function ($value) {
                $this->crud->addClause('where', 'contratohistorico.tipo_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Observação
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroObservacao()
    {
        $campo = [
            'name' => 'observacao',
            'type' => 'text',
            'label' => 'Observação'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where',
                    'contratohistorico.observacao', 'ilike',
                    '%' . $value . '%'
                );
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Número do Contrato (do Histórico)
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroHistoricoNumeroContrato()
    {
        $campo = [
            'name' => 'numcontrato',
            'type' => 'text',
            'label' => 'Núm. Contrato Hist.'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where',
                    'contratohistorico.numero', 'ilike',
                    '%' . $value . '%'
                );
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Categoria
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroCategoria()
    {
        $campo = [
            'name' => 'categoria',
            'type' => 'select2',
            'label' => 'Categoria'
        ];

        $categorias = $this->retornaCategoriasParaCombo();

        $this->crud->addFilter(
            $campo,
            $categorias,
            function ($value) {
                $this->crud->addClause('where', 'contratohistorico.categoria_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Fundamento Legal
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroFundamentoLegal()
    {
        $campo = [
            'name' => 'fundamentolegal',
            'type' => 'text',
            'label' => 'Fundamento Legal'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $this->crud->addClause('where',
                    'contratohistorico.fundamento_legal', 'ilike',
                    '%' . $value . '%'
                );
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Modalidade
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroModalidade()
    {
        $campo = [
            'name' => 'modalidade',
            'type' => 'select2',
            'label' => 'Modalidade'
        ];

        $modalidades = $this->retornaModalidadeParaCombo();

        $this->crud->addFilter(
            $campo,
            $modalidades,
            function ($value) {
                $this->crud->addClause('where', 'contratohistorico.modalidade_id', $value);
            }
        );
    }

    /**
     * Adiciona o filtro ao campo Valor Global (do Histórico)
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function aplicaFiltroHistoricoValorGlobal()
    {
        $campo = [
            'name' => 'valorglobal',
            'type' => 'range',
            'label' => 'Valor Global Hist.'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $range = json_decode($value);

                if ($range->from) {
                    $this->crud->addClause('where',
                        'contratohistorico.valor_global', '>=', (float)$range->from
                    );
                }

                if ($range->to) {
                    $this->crud->addClause('where',
                        'contratohistorico.valor_global', '<=', (float)$range->to
                    );
                }
            }
        );
    }

    /**
     * Retorna array de Tipos para combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaTiposParaCombo()
    {
        return $this->retornaRegistrosCodigoItensParaCombo(Codigo::CODIGO_TIPO_DE_CONTRATO);
    }

    /**
     * Retorna array de Categorias para combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaCategoriasParaCombo()
    {
        return $this->retornaRegistrosCodigoItensParaCombo(Codigo::CODIGO_CATEGORIA_CONTRATO);
    }

    /**
     * Retorna array de Modalidade para combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaModalidadeParaCombo()
    {
        return $this->retornaRegistrosCodigoItensParaCombo(Codigo::CODIGO_MODALIDADE_LICITACAO);
    }

    /**
     * Retorna array de registros Código Itens para uso em combo de filtro
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaRegistrosCodigoItensParaCombo($codigo)
    {
        $dados = Codigoitem::select('descricao', 'id');

        $dados->where('codigo_id', $codigo);
        $dados->orderBy('descricao');

        return $dados->pluck('descricao', 'id')->toArray();
    }

}
