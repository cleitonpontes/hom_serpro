<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoitemRequest as StoreRequest;
use App\Http\Requests\ContratoitemRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ContratoitemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoitemCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratoitem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/' . $contrato_id . '/itens');
        $this->crud->setEntityNameStrings('Item Contratado', 'Itens Contratados');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contratoitem_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratoitem_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratoitem_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo CATMAT e CATSER');
        })
            ->pluck('descricao', 'id')
            ->toArray();


        $campos = $this->Campos($contrato_id, $tipos);
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in ContratoitemRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
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
                'name' => 'getTipo',
                'label' => 'Tipo Item', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
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
                'name' => 'getCatmatsergrupo',
                'label' => 'Item Grupo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCatmatsergrupo', // the method in your Model
                'limit' => 1000,
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
                'name' => 'getCatmatseritem',
                'label' => 'Item', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCatmatseritem', // the method in your Model
                'limit' => 1000,
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
                'name' => 'descricao_complementar',
                'label' => 'Descriçao Complementar',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'quantidade',
                'label' => 'Quantidade', // Table column heading
                'type' => 'number',
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
                'name' => 'formatValorUnitarioItem',
                'label' => 'Valor Unitário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorUnitarioItem', // the method in your Model
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
                'name' => 'formatValorTotalItem',
                'label' => 'Valor Total', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorTotalItem', // the method in your Model
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
        ];

        return $colunas;
    }

    public function Campos($contrato, $tipos)
    {

        $campos = [
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato,
            ],
            [
                // select_from_array
                'name' => 'tipo_id',
                'label' => "Tipo",
                'type' => 'select_from_array',
                'options' => $tipos,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
//            [ // select2_from_ajax: 1-n relationship
//                'name' => 'grupo_id',
//                // the column that contains the ID of that connected entity
//                'label' => "Grupo",
//                // Table column heading
//                'type' => 'select2_from_ajax',
//                'entity' => 'grupo',
//                // the method that defines the relationship in your Model
//                'attribute' => 'descricao',
//                // foreign key attribute that is shown to user
////                'process_results_template' => 'gescon.process_results', // foreign key attribute that is shown to user
//                'data_source' => url('api/catmatsergrupo'),
//                // url to controller search function (with /{id} should return model)
//                'placeholder' => 'Selecione o Grupo',
//                // placeholder for the select
//                'minimum_input_length' => 0,
//                // minimum characters to type before querying results
//                'dependencies' => ['tipo_id'],
//                // when a dependency changes, this select2 is reset to null
//                // ‘method'                    => ‘GET’, // optional - HTTP method to use for the AJAX call (GET, POST)
//            ],
            [ // select2_from_ajax: 1-n relationship
                'name' => 'catmatseritem_id',
                // the column that contains the ID of that connected entity
                'label' => "Item",
                // Table column heading
                'type' => 'select2_from_ajax',
                'entity' => 'item',
                // the method that defines the relationship in your Model
                'attribute' => 'descricao',
                // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_catmatseritem',
                // foreign key attribute that is shown to user
                'data_source' => url('api/catmatseritem'),
                // url to controller search function (with /{id} should return model)
                'placeholder' => 'Selecione o Item',
                // placeholder for the select
                'minimum_input_length' => 0,
                // minimum characters to type before querying results
                'dependencies' => ['tipo_id'],
                // when a dependency changes, this select2 is reset to null
                // ‘method'                    => ‘GET’, // optional - HTTP method to use for the AJAX call (GET, POST)
            ],
            [
                'name' => 'descricao_complementar',
                'label' => 'Descrição Complementar',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
            ],
            [   // Number
                'name' => 'quantidade',
                'label' => 'Quantidade',
                'type' => 'number',
                // optionals
//                'attributes' => [
//                    'id' => 'valorunitario',
//                ], // allow decimals
//                'prefix' => "R$",
            ],
            [   // Number
                'name' => 'valorunitario',
                'label' => 'Valor Unitário',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valorunitario',
                ], // allow decimals
                'prefix' => "R$",
            ],
            [   // Number
                'name' => 'valortotal',
                'label' => 'Valor Total',
                'type' => 'money_fatura',
                // optionals
                'attributes' => [
                    'id' => 'valortotal',
                ], // allow decimals
                'prefix' => "R$",
            ],

        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $valorunitario = str_replace(',', '.', str_replace('.', '', $request->input('valorunitario')));
        $request->request->set('valorunitario', number_format(floatval($valorunitario), 2, '.', ''));

        $valortotal = str_replace(',', '.', str_replace('.', '', $request->input('valortotal')));
        $request->request->set('valortotal', number_format(floatval($valortotal), 2, '.', ''));


        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        $valorunitario = str_replace(',', '.', str_replace('.', '', $request->input('valorunitario')));
        $request->request->set('valorunitario', number_format(floatval($valorunitario), 2, '.', ''));

        $valortotal = str_replace(',', '.', str_replace('.', '', $request->input('valortotal')));
        $request->request->set('valortotal', number_format(floatval($valortotal), 2, '.', ''));

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contrato_id',
            'tipo_id',
            'grupo_id',
            'catmatseritem_id',
            'valorunitario',
            'valortotal',
        ]);

        return $content;
    }
}
