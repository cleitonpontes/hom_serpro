<?php

namespace App\Http\Controllers\Transparencia;

use App\Models\Codigoitem;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ConsultaContratosCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ConsultaContratosCrudController extends ConsultaContratoBaseCrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contrato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transparencia/contratos');
        $this->crud->setEntityNameStrings('Consulta Contrato', 'Consulta Contratos');

        $this->crud->addClause('select', 'contratos.*');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $this->crud->addClause('where', 'contratos.situacao', '=', true);
        $this->crud->addClause('where', 'unidades.sigilo', '=', false);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->defineConfiguracaoPadrao();
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'fornecedor_id',
            'unidade_id',
            'tipo_id',
            'categoria_id',
            'fundamento_legal',
            'modalidade_id',
            'licitacao_numero',
            'data_assinatura',
            'data_publicacao',
            'valor_inicial',
            'valor_global',
            'valor_parcela',
            'valor_acumulado',
            'total_despesas_acessorias',
            'receita_despesa',
            'subcategoria_id',
            'situacao_siasg',
            'situacao',
        ]);

        return $content;
    }

    protected function aplicaFiltrosEspecificos(): void
    {

        $this->crud->addFilter(
            [
            'name' => 'numero',
            'type' => 'text',
            'label' => 'N??mero Contrato'
            ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'contratos.numero', 'LIKE', "%$value%");
            }
        );

        $this->crud->addFilter([
            'name' => 'receita_despesa',
            'type' => 'select2_multiple',
            'label' => 'Receita / Despesa'
        ], [
            'R' => 'Receita',
            'D' => 'Despesa',
        ], function ($value) {
            $this->crud->addClause(
                'whereIn',
                'contratos.receita_despesa',
                json_decode($value)
            );
        });

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $this->crud->addFilter(
            [
                'name' => 'tipo_contrato',
                'type' => 'select2_multiple',
                'label' => 'Tipo'
            ],
            $tipos,
            function ($value) {
                $this->crud->addClause(
                    'whereIn',
                    'contratos.tipo_id',
                    json_decode($value)
                );
            }
        );

        $categorias = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Categoria Contrato');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $this->crud->addFilter(
            [
                'name' => 'categorias',
                'type' => 'select2_multiple',
                'label' => 'Categorias'
            ],
            $categorias,
            function ($values) {
                $this->crud->addClause(
                    'whereIn',
                    'contratos.categoria_id',
                    json_decode($values)
                );
            }
        );

        $this->crud->addFilter(
            [
                'type' => 'date_range',
                'name' => 'vigencia_inicio',
                'label' => 'Vig??ncia Inicio'
            ],
            false,
            function ($value) {
                // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratos.vigencia_inicio', '>=', $dates->from);
                $this->crud->addClause('where', 'contratos.vigencia_inicio', '<=', $dates->to . ' 23:59:59');
            }
        );

        $this->crud->addFilter(
            [
                'type' => 'date_range',
                'name' => 'vigencia_fim',
                'label' => 'Vig??ncia Fim'
            ],
            false,
            function ($value) {
                // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratos.vigencia_fim', '>=', $dates->from);
                $this->crud->addClause('where', 'contratos.vigencia_fim', '<=', $dates->to . ' 23:59:59');
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'valor_global',
                'type' => 'range',
                'label' => 'Valor Global',
                'label_from' => 'Vlr M??nimo',
                'label_to' => 'Vlr M??ximo'
            ],
            false,
            function ($value) {
                // if the filter is active
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'contratos.valor_global', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'contratos.valor_global', '<=', (float)$range->to);
                }
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'valor_parcela',
                'type' => 'range',
                'label' => 'Valor Parcela',
                'label_from' => 'Vlr M??nimo',
                'label_to' => 'Vlr M??ximo'
            ],
            false,
            function ($value) {
                // if the filter is active
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'contratos.valor_parcela', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'contratos.valor_parcela', '<=', (float)$range->to);
                }
            }
        );
    }

    protected function adicionaColunasEspecificasNaListagem(): void
    {
        $this->crud->addColumns([
            [
                'name' => 'getUnidadeOrigem',
                'label' => 'Unidade Gestora Origem do Contrato',
                'type' => 'model_function',
                'function_name' => 'getUnidadeOrigem',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getReceitaDespesa',
                'label' => 'Receita / Despesa',
                'type' => 'model_function',
                'function_name' => 'getReceitaDespesa',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'numero',
                'label' => 'N??mero Contrato',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'unidades_requisitantes',
                'label' => 'Unidades Requisitantes',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getTipo',
                'label' => 'Tipo',
                'type' => 'model_function',
                'function_name' => 'getTipo',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getCategoria',
                'label' => 'Categoria',
                'type' => 'model_function',
                'function_name' => 'getCategoria',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getSubCategoria',
                'label' => 'Subcategoria',
                'type' => 'model_function',
                'function_name' => 'getSubCategoria',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor',
                'type' => 'model_function',
                'function_name' => 'getFornecedor',
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                }
            ],
            [
                'name' => 'processo',
                'label' => 'Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'objeto',
                'label' => 'Objeto',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'info_complementar',
                'label' => 'Informa????es Complementares',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'vigencia_inicio',
                'label' => 'Vig. In??cio',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'vigencia_fim',
                'label' => 'Vig. Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrGlobal',
                'label' => 'Valor Global',
                'type' => 'model_function',
                'function_name' => 'formatVlrGlobal',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'num_parcelas',
                'label' => 'N??m. Parcelas',
                'type' => 'number',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrParcela',
                'label' => 'Valor Parcela',
                'type' => 'model_function',
                'function_name' => 'formatVlrParcela',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatVlrAcumulado',
                'label' => 'Valor Acumulado',
                'type' => 'model_function',
                'function_name' => 'formatVlrAcumulado',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'formatTotalDespesasAcessorias',
                'label' => 'Total Despesas Acess??rias',
                'type' => 'model_function',
                'function_name' => 'formatTotalDespesasAcessorias',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'historico',
                'label' => 'Hist??rico',
                'type' => 'contratohistoricotable',
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'despesasacessorias',
                'label' => 'Despesas Acess??rias',
                'type' => 'table',
                'columns' => [
                    'descricao_complementar' => 'Descri????o',
                    'vencimento' => 'Vencimento',
                    'valor' => 'Valor'
                ],
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'empenho',
                'label' => 'Empenhos',
                'type' => 'empenhotable',
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'faturas',
                'label' => 'Faturas',
                'type' => 'table',
                'columns' => [
                    'numero' => 'N??mero',
                    'emissao' => 'Data Emiss??o',
                    'processo' => 'Processo',
                    'ateste' => 'Data Ateste',
                    'valor' => 'Valor'
                ],
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'garantias',
                'label' => 'Garantias',
                'type' => 'table',
                'columns' => [
                    'descricao_tipo' => 'Tipo',
                    'vencimento' => 'Vencimento',
                    'valor' => 'Valor'
                ],
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'itens',
                'label' => 'Itens',
                'type' => 'table',
                'columns' => [
                    'descricao_tipo' => 'Tipo',
                    // 'descricao_grupo' => 'Grupo',
                    'descricao_item' => 'Item',
                    // 'descricao_complementar' => 'Descri????o',
                    'quantidade' => 'Quantidade',
                    'valorunitario' => 'Valor Unit??rio',
                    'valortotal' => 'Valor Total'
                ],
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'prepostos',
                'label' => 'Prepostos',
                'type' => 'table',
                'columns' => [
                    'masked_cpf' => 'CPF',
                    'nome' => 'Nome'
                ],
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'responsaveis',
                'label' => 'Respons??veis',
                'type' => 'table',
                'columns' => [
                    'masked_cpf' => 'CPF',
                    'usuario_nome' => 'Nome',
                    'descricao_tipo' => 'Tipo'
                ],
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => true,
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'arquivos',
                'disk' => 'local',
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ]
        ]);
    }
}
