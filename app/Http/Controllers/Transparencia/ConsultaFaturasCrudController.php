<?php

namespace App\Http\Controllers\Transparencia;

use App\Models\Fornecedor;
use App\Models\Justificativafatura;
use App\Models\Orgao;
use App\Models\Tipolistafatura;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ConsultaFaturasRequest as StoreRequest;
use App\Http\Requests\ConsultaFaturasRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultaFaturasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ConsultaFaturasCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $orgao_cod = request()->input('orgao') ?? '';

        $this->crud->setModel('App\Models\Contratofatura');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transparencia/faturas');
        $this->crud->setEntityNameStrings('Consulta Faturas', 'Consulta Faturas');

        $this->crud->addClause('select', 'contratofaturas.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratofaturas.contrato_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'orgaos', 'orgaos.id', '=', 'unidades.orgao_id');
        $this->crud->addClause('where', 'contratos.situacao', '=', true);
        $this->crud->orderBy('contratofaturas.ateste', 'asc');


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

        $tipolista = Tipolistafatura::where('situacao',true)
            ->pluck('nome', "id")
            ->toArray();

        $justificativa = Justificativafatura::where('situacao',true)
            ->pluck('nome', "id")
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
            'name' => 'tipolista',
            'type' => 'select2',
            'label' => 'Tipo Lista'
        ], function () use ($tipolista) {
            return $tipolista;
        }, function ($value) {
            $this->crud->addClause('where', 'contratofaturas.tipolistafatura_id', $value);
        });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'justificativa',
            'type' => 'select2',
            'label' => 'Justificativa'
        ], function () use ($justificativa) {
            return $justificativa;
        }, function ($value) {
            $this->crud->addClause('where', 'contratofaturas.justificativafatura_id', $value);
        });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'situacao',
            'type' => 'dropdown',
            'label' => 'Situação'
        ], config('app.situacao_fatura'), function ($value) { // if the filter is active
            $this->crud->addClause('where', 'contratofaturas.situacao', $value);
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
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getTipoLista',
                'label' => 'Tipo Lista', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoLista', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getJustificativa',
                'label' => 'Justificativa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getJustificativa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('justificativafatura.nome', 'like', "%" . strtoupper($searchTerm) . "%");
//                },
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFornecedor', // the method in your Model
                'limit' => 1000,
                'orderable' => true,
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
                'name' => 'getNumero',
                'label' => 'Número',
                'type' => 'model_function',
                'function_name' => 'getNumero',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratofaturas.numero', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'emissao',
                'label' => 'Dt. Emissão',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'ateste',
                'label' => 'Dt. Ateste',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vencimento',
                'label' => 'Dt. Vencimento',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'prazo',
                'label' => 'Prazo Pagamento',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatValor',
                'label' => 'Valor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValor', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratofaturas.valor', 'like', "%" . $searchTerm . "%");
                },
            ],
            [
                'name' => 'formatJuros',
                'label' => 'Juros', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatJuros', // the method in your Model
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
                'name' => 'formatMulta',
                'label' => 'Multa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatMulta', // the method in your Model
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
                'name' => 'formatGlosa',
                'label' => 'Glosa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatGlosa', // the method in your Model
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
                'name' => 'formatValorLiquido',
                'label' => 'Valor Líquido a pagar', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorLiquido', // the method in your Model
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
                'name' => 'getProcesso',
                'label' => 'Processo',
                'type' => 'model_function',
                'function_name' => 'getProcesso',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratofaturas.processo', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'protocolo',
                'label' => 'Dt. Protocolo', // Table column heading
                'type' => 'date',
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
                'name' => 'infcomplementar',
                'label' => 'Informações Complementares',
                'type' => 'text',
                'limit' => 255,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'repactuacao',
                'label' => 'Repactuação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'mesref',
                'label' => 'Mês Referência', // Table column heading
                'type' => 'text',
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
                'name' => 'anoref',
                'label' => 'Ano Referência', // Table column heading
                'type' => 'text',
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
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'select_from_array',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => config('app.situacao_fatura')
            ],
        ];

        return $colunas;

    }


    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contrato_id',
            'tipolistafatura_id',
            'justificativafatura_id',
            'valor',
            'juros',
            'multa',
            'glosa',
            'valorliquido',
        ]);


        return $content;
    }
}
