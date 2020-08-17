<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratodespesaacessoriaRequest as StoreRequest;
use App\Http\Requests\ContratodespesaacessoriaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Route;

/**
 * Class ContratodespesasacessoriaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoDespesaAcessoriaCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'));

        if (!$contrato->first()) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratodespesaacessoria');
        $this->crud->setRoute(config('backpack.base.route_prefix') . "/gescon/contrato/$contrato_id/despesaacessoria");
        $this->crud->setEntityNameStrings('Despesa Acessória', 'Despesas Acessórias');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratodespesaacessoria.contrato_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addClause('where', 'unidades.id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'contratodespesaacessoria.*');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contratodespesaacessoria_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratodespesaacessoria_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratodespesaacessoria_deletar')) ? $this->crud->allowAccess('delete') : null;

        $this->crud->addColumns($this->Colunas());
        $this->crud->addFields($this->Campos(
            $contrato->pluck('numero', 'id')
                ->toArray())
        );
        $this->adicionaFiltros();


        // add asterisk for fields that are required in ContratodespesaacessoriaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function adicionaFiltros()
    {
        $this->adicionaFiltroNumeroContrato();
        $this->adicionaFiltroFornecedor();
        $this->adicionaFiltroTipoDespesa();
        $this->adicionaFiltroRecorrenciaDespesa();
        $this->adicionaFiltroDataVencimento();
    }

    public function adicionaFiltroDataVencimento()
    {
        $campo = [
            'name' => 'vencimento',
            'type' => 'date_range',
            'label' => 'Vencimento'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratodespesaacessoria.vencimento', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratodespesaacessoria.vencimento', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    public function adicionaFiltroTipoDespesa()
    {
        $campo = [
            'name' => 'tipo_id',
            'type' => 'select2',
            'label' => 'Tipo Despesa'
        ];

        $tiposLista = $this->retornaTipoDespesa();

        $this->crud->addFilter(
            $campo,
            $tiposLista,
            function ($value) {
                $this->crud->addClause('where', 'contratodespesaacessoria.tipo_id', $value);
            }
        );
    }

    public function adicionaFiltroRecorrenciaDespesa()
    {
        $campo = [
            'name' => 'recorrencia_id',
            'type' => 'select2',
            'label' => 'Recorrência Despesa'
        ];

        $tiposLista = $this->retornaRecorrenciaDespesa();

        $this->crud->addFilter(
            $campo,
            $tiposLista,
            function ($value) {
                $this->crud->addClause('where', 'contratodespesaacessoria.recorrencia_id', $value);
            }
        );
    }

    public function adicionaFiltroFornecedor()
    {
        $campo = [
            'name' => 'cpf_cnpj',
            'type' => 'select2',
            'label' => 'Fornecedor'
        ];

        $fornecedores = $this->retornaFornecedores();

        $this->crud->addFilter(
            $campo,
            $fornecedores,
            function ($value) {
                $this->crud->addClause('where', 'fornecedores.cpf_cnpj_idgener', $value);
            }
        );
    }

    public function adicionaFiltroNumeroContrato()
    {
        $campo = [
            'name' => 'contrato',
            'type' => 'select2',
            'label' => 'Núm. Contrato'
        ];

        $contratos = $this->retornaContratos();

        $this->crud->addFilter(
            $campo,
            $contratos,
            function ($value) {
                $this->crud->addClause('where', 'contratos.numero', $value);
            }
        );
    }

    private function retornaContratos()
    {
        $dados = Contrato::select(
            DB::raw("CONCAT(contratos.numero,' | ',fornecedores.cpf_cnpj_idgener,' - ',fornecedores.nome) AS nome"), 'numero'
        );
        $dados->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $dados->where('unidade_id', session()->get('user_ug_id'));
        $dados->where('situacao', true);
        $dados->orderBy('numero'); // 'data_publicacao'

        return $dados->pluck('nome', 'numero')->toArray();
    }

    private function retornaFornecedores()
    {
        $dados = Fornecedor::select(
            DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS descricao"), 'cpf_cnpj_idgener'
        );

        $dados->whereHas('contratos', function ($c) {
            $c->where('situacao', true);
        });

        return $dados->pluck('descricao', 'cpf_cnpj_idgener')->toArray();
    }

    private function retornaTipoDespesa()
    {
        $tipoDespesa = Codigoitem::whereHas('codigo', function ($c) {
            $c->where('descricao', 'Tipo Despesa Acessória');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id');

        return $tipoDespesa->toArray();
    }

    private function retornaRecorrenciaDespesa()
    {
        $recorrenciaDespesa = Codigoitem::whereHas('codigo', function ($c) {
            $c->where('descricao', 'Recorrência Despesa Acessória');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id');

        return $recorrenciaDespesa->toArray();
    }

    public function Colunas()
    {
        return [
            [
                'name' => 'getUnidade',
                'label' => 'Unidade',
                'type' => 'model_function',
                'function_name' => 'getUnidade',
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('unidades.codigo', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('unidades.nomeresumido', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'limit' => 150,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contratos.numero', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFornecedor', // the method in your Model
                'limit' => 150,
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
                'name' => 'getTipoDespesa',
                'label' => 'Tipo Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoDespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getRecorrenciaDespesa',
                'label' => 'Recorrência Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getRecorrenciaDespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'descricao_complementar',
                'label' => 'Descrição Complementar', // Table column heading
                'type' => 'test',
                'limit' => 255,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vencimento',
                'label' => 'Vencimento', // Table column heading
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
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

            ],
        ];

    }

    public function campos($contrato)
    {
        $tipoDespesa = Codigoitem::whereHas('codigo', function ($c) {
            $c->where('descricao', 'Tipo Despesa Acessória');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $recorrenciaDespesa = Codigoitem::whereHas('codigo', function ($c) {
            $c->where('descricao', 'Recorrência Despesa Acessória');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        return [
            [
                'name' => 'contrato_id',
                'label' => "Contrato",
                'type' => 'select2_from_array',
                'options' => $contrato,
                'allows_null' => false,
            ],
            [
                'name' => 'tipo_id',
                'label' => "Tipo Despesa",
                'type' => 'select2_from_array',
                'options' => $tipoDespesa,
                'allows_null' => true,
            ],
            [
                'name' => 'recorrencia_id',
                'label' => "Recorrência Despesa",
                'type' => 'select2_from_array',
                'options' => $recorrenciaDespesa,
                'allows_null' => true,
            ],
            [   // Date
                'name' => 'descricao_complementar',
                'label' => 'Descrição Complementar',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [   // Date
                'name' => 'vencimento',
                'label' => 'Vencimento',
                'type' => 'date',
            ],
            [   // Number
                'name' => 'valor',
                'label' => 'Valor',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'valor',
                ], // allow decimals
                'prefix' => "R$",
                // 'suffix' => ".00",
            ],
        ];
    }

    public function store(StoreRequest $request)
    {
        $valor = str_replace(',', '.', str_replace('.', '', $request->input('valor')));
        $request->request->set('valor', number_format(floatval($valor), 2, '.', ''));

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $valor = str_replace(',', '.', str_replace('.', '', $request->input('valor')));
        $request->request->set('valor', number_format(floatval($valor), 2, '.', ''));

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('tipo_id');
        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('recorrencia_id');
        $this->crud->removeColumn('valor');


        return $content;
    }
}
