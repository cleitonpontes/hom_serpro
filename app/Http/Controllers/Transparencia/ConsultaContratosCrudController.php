<?php

namespace App\Http\Controllers\Transparencia;

use App\Models\Codigoitem;
use App\Models\Fornecedor;
use App\Models\Orgao;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use function foo\func;

/**
 * Class ConsultaContratosCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ConsultaContratosCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $orgao_cod = request()->input('orgao') ?? '';

        $this->crud->setModel('App\Models\Contrato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transparencia/contratos');
        $this->crud->setEntityNameStrings('Consulta Contrato', 'Consulta Contratos');
        $this->crud->addClause('select', 'contratos.*');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
//        if (isset($orgao->id)) {
//            $this->crud->addClause('where', 'orgaos.id', '=', $orgao->id);
//        }
        $this->crud->addClause('where', 'contratos.situacao', '=', true);


        $this->crud->enableExportButtons();
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('create');
//        $this->crud->denyAccess('revisions');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
//        $this->crud->removeAllButtonsFromStack('line');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $orgaos = Orgao::select(DB::raw("CONCAT(codigo,' - ',nome) AS nome"), 'codigo')
            ->whereHas('unidades', function ($u) {
                $u->whereHas('contratos', function ($c) {
                    $c->where('situacao', true);
                });
            })
            ->pluck('nome', "codigo")
            ->toArray();

        $unidades = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'codigo')
            ->whereHas('contratos', function ($u) {
                $u->where('situacao', true);
            })
            ->pluck('nome', "codigo")
            ->toArray();

        if ($orgao_cod) {
            $unidades = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'codigo')
                ->whereHas('contratos', function ($u) {
                    $u->where('situacao', true);
                })
                ->whereHas('orgao', function ($o) use ($orgao_cod) {
                    $o->where('codigo', $orgao_cod);
                })
                ->pluck('nome', "codigo")
                ->toArray();
        }

        $fonecedores = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'cpf_cnpj_idgener')
            ->whereHas('contratos', function ($u) {
                $u->where('situacao', true);
            })
            ->pluck('nome', "cpf_cnpj_idgener")
            ->toArray();

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $categorias = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Categoria Contrato');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();


        $this->crud->addFilter([ // dropdown filter
            'name' => 'orgao',
            'type' => 'select2',
            'label' => 'Órgão'
        ], function () use ($orgaos) {
            return $orgaos;
        }, function ($value) {
            $this->crud->addClause('where', 'orgaos.codigo', $value);
        });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'unidade',
            'type' => 'select2',
            'label' => 'Unidade Gestora'
        ], function () use ($unidades) {
            return $unidades;
        }, function ($value) {
            $this->crud->addClause('where', 'unidades.codigo', $value);
        });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'fornecedor',
            'type' => 'select2',
            'label' => 'Fornecedor'
        ], function () use ($fonecedores) {
            return $fonecedores;
        }, function ($value) {
            $this->crud->addClause('where', 'fornecedores.cpf_cnpj_idgener', $value);
        });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'receita_despesa',
            'type' => 'dropdown',
            'label' => 'Receita / Despesa'
        ], [
            'R' => 'Receita',
            'D' => 'Despesa',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'contratos.receita_despesa', $value);
        });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'tipo_contrato',
            'type' => 'dropdown',
            'label' => 'Tipo'
        ], $tipos, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'contratos.tipo_id', $value);
        });


        $this->crud->addFilter([ // select2_multiple filter
            'name' => 'categorias',
            'type' => 'select2_multiple',
            'label' => 'Categorias'
        ], function () use ($categorias) {
            return $categorias;
        }, function ($values) { // if the filter is active
            foreach (json_decode($values) as $key => $value) {
                $this->crud->addClause('where', 'contratos.categoria_id', $value);
            }
        });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'vigencia_inicio',
            'label' => 'Vigência Inicio'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratos.vigencia_inicio', '>=', $dates->from);
                $this->crud->addClause('where', 'contratos.vigencia_inicio', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'vigencia_fim',
            'label' => 'Vigência Fim'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratos.vigencia_fim', '>=', $dates->from);
                $this->crud->addClause('where', 'contratos.vigencia_fim', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([
            'name' => 'valor_global',
            'type' => 'range',
            'label' => 'Valor Global',
            'label_from' => 'Vlr Mínimo',
            'label_to' => 'Vlr Máximo'
        ],
            false,
            function ($value) { // if the filter is active
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'contratos.valor_global', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'contratos.valor_global', '<=', (float)$range->to);
                }
            });

        $this->crud->addFilter([
            'name' => 'valor_parcela',
            'type' => 'range',
            'label' => 'Valor Parcela',
            'label_from' => 'Vlr Mínimo',
            'label_to' => 'Vlr Máximo'
        ],
            false,
            function ($value) { // if the filter is active
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'contratos.valor_parcela', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'contratos.valor_parcela', '<=', (float)$range->to);
                }
            });


    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getOrgao',
                'label' => 'Órgão', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getOrgao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidadeOrigem',
                'label' => 'Unidade Gestora Origem', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidadeOrigem', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getReceitaDespesa',
                'label' => 'Receita / Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getReceitaDespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numero',
                'label' => 'Número Contrato',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'unidades_requisitantes',
                'label' => 'Unidades Requisitantes',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model

                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getCategoria',
                'label' => 'Categoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCategoria', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getSubCategoria',
                'label' => 'Subcategoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSubCategoria', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFornecedor', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'processo',
                'label' => 'Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'objeto',
                'label' => 'Objeto',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'info_complementar',
                'label' => 'Informações Complementares',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_inicio',
                'label' => 'Vig. Início',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_fim',
                'label' => 'Vig. Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrGlobal',
                'label' => 'Valor Global', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrGlobal', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'num_parcelas',
                'label' => 'Núm. Parcelas',
                'type' => 'number',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrParcela',
                'label' => 'Valor Parcela', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrParcela', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrAcumulado',
                'label' => 'Valor Acumulado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrAcumulado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatTotalDespesasAcessorias',
                'label' => 'Total Despesas Acessórias', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatTotalDespesasAcessorias', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'historico',
                'label' => 'Histórico',
                'type' => 'contratohistoricotable',
                'visibleInTable' => false,
                'visibleInModal' => false, // would make the modal too big
                'visibleInExport' => false, // not important enough
                'visibleInShow' => true,
            ],
            [
                'name' => 'empenho',
                'label' => 'Empenhos',
                'type' => 'empenhotable',
                'visibleInTable' => false,
                'visibleInModal' => false, // would make the modal too big
                'visibleInExport' => false, // not important enough
                'visibleInShow' => true,
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'arquivos',
                'disk' => 'local',
                'visibleInTable' => false,
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true,
            ]
        ];

        return $colunas;

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

}
