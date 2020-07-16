<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SubrogacaoRequest as StoreRequest;
use App\Http\Requests\SubrogacaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class SubrogacaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SubrogacaoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Subrogacao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/subrogacao');
        $this->crud->setEntityNameStrings('Sub-rogação', 'Sub-rogações');
        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'subrogacoes.contrato_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('where', 'subrogacoes.unidadeorigem_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'subrogacoes.*');
        $this->crud->orderBy('data_termo', 'desc');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('subrogacao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('subrogacao_deletar')) ? $this->crud->allowAccess('delete') : null;


        $this->crud->addColumns($this->Colunas());
        $this->crud->addFields($this->Campos());

        // add asterisk for fields that are required in SubrogacaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        return [
            [
                'name' => 'getUnidadeOrigem',
                'label' => 'Unidade Origem',
                'type' => 'model_function',
                'function_name' => 'getUnidadeOrigem',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhereHas('unidadeOrigem2', function ($q) use ($column, $searchTerm) {
                        $q->orWhere('codigo', 'like', "%" . strtoupper($searchTerm) . "%");
                        $q->orWhere('nomeresumido', 'like', "%" . strtoupper($searchTerm) . "%");
                    });

                },
            ],
            [
                'name' => 'getContrato',
                'label' => 'Contrato - Fornecedor', // Table column heading
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
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getUnidadeDestino',
                'label' => 'Unidade Destino', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidadeDestino', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhereHas('unidadeDestino', function ($q) use ($column, $searchTerm) {
                        $q->orWhere('codigo', 'like', "%" . strtoupper($searchTerm) . "%");
                        $q->orWhere('nomeresumido', 'like', "%" . strtoupper($searchTerm) . "%");
                    });

                },
            ],
            [
                'name' => 'data_termo',
                'label' => 'Data Termo', // Table column heading
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ]
        ];

    }

    public function campos()
    {
        $contratos = Contrato::select(DB::raw("CONCAT(contratos.numero,' | ',fornecedores.cpf_cnpj_idgener,' - ',fornecedores.nome) AS nome"), 'contratos.id')
            ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
            ->where('unidade_id',session()->get('user_ug_id'))
            ->where('situacao',true)
            ->orderBy('contratos.numero', 'asc')->pluck('nome', 'id')->toArray();


        $ug = Unidade::find(session()->get('user_ug_id'));

        return [
            [ // select_from_array
                'name' => 'unidadeorigem_id',
                'label' => "Unidade Origem",
                'type' => 'select2_from_array',
                'options' => [$ug->id => $ug->codigo.' - '.$ug->nomeresumido],
                'allows_null' => false,
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Contrato",
                'type' => 'select2_from_array',
                'options' => $contratos,
                'allows_null' => true,
            ],
            [
                // 1-n relationship
                'label' => "Unidade Destino", // Table column heading
                'type' => "select2_from_ajax",
                'name' => 'unidadedestino_id', // the column that contains the ID of that connected entity
                'entity' => 'unidadeDestino', // the method that defines the relationship in your Model
                'attribute' => "codigo", // foreign key attribute that is shown to user
                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_unidade',
                'model' => "App\Models\Unidade", // foreign key model
                'data_source' => url("api/unidade"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a Unidade", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
            ],
            [   // Date
                'name' => 'data_termo',
                'label' => 'Data Termo',
                'type' => 'date',
            ],
        ];
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('unidadeorigem_id');
        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('unidadedestino_id');


        return $content;
    }
}
