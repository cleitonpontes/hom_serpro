<?php

namespace App\Http\Controllers\Transparencia;

use App\Models\Fornecedor;
use App\Models\Orgao;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ConsultaTerceirizadosRequest as StoreRequest;
use App\Http\Requests\ConsultaTerceirizadosRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultaTerceirizadosCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ConsultaTerceirizadosCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $orgao_cod = request()->input('orgao') ?? '';

        $this->crud->setModel('App\Models\Contratoterceirizado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transparencia/terceirizados');
        $this->crud->setEntityNameStrings('Consulta Terceirizados', 'Consulta Terceirizados');

        $this->crud->addClause('select', 'contratoterceirizados.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratoterceirizados.contrato_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $this->crud->addClause('where', 'contratos.situacao', '=', true);

        $this->crud->enableExportButtons();
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        //filters
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
            'name' => 'situacao',
            'type' => 'dropdown',
            'label' => 'Situação'
        ], [1 => 'Ativo', 0 => 'Inativo'], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'contratoterceirizados.situacao', $value);
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
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
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
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getCpf',
                'label' => 'CPF', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCpf', // the method in your Model
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratoterceirizados.cpf', 'like', '%' . $searchTerm . '%');
                },
            ],
            [
                'name' => 'getNome',
                'label' => 'Nome', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNome', // the method in your Model
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratoterceirizados.nome', 'like', '%' . strtoupper($searchTerm) . '%');
                },
            ],
            [
                'name' => 'getFuncao',
                'label' => 'Função', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFuncao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhereHas('funcao', function ($q) use ($searchTerm) {
                        $q->where('descricao', 'like', '%' . $searchTerm . '%');
                    });
                },
            ],
            [
                'name' => 'descricao_complementar',
                'label' => 'Descrição Complementar',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'jornada',
                'label' => 'Jornada',
                'type' => 'number',
            ],
            [
                'name' => 'unidade',
                'label' => 'Unidade',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrSalario',
                'label' => 'Salário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrSalario', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
            [
                'name' => 'formatVlrCusto',
                'label' => 'Custo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrCusto', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
            [
                'name' => 'getEscolaridade',
                'label' => 'Escolaridade', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getEscolaridade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
               'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhereHas('escolaridade', function ($q) use ($searchTerm) {
                        $q->where('descricao', 'like', '%' . $searchTerm . '%');
                    });
                },
            ],
            [   // Date
                'name' => 'data_inicio',
                'label' => 'Data Início',
                'type' => 'date',
            ],
            [   // Date
                'name' => 'data_fim',
                'label' => 'Data Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
        ];

        return $colunas;

    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('funcao_id');
        $this->crud->removeColumn('escolaridade_id');
        $this->crud->removeColumn('custo');
        $this->crud->removeColumn('salario');
        $this->crud->removeColumn('nome');
        $this->crud->removeColumn('cpf');

        return $content;
    }

}
