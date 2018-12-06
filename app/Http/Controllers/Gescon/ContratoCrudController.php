<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoRequest as StoreRequest;
use App\Http\Requests\ContratoRequest as UpdateRequest;

/**
 * Class ContratoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contrato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato');
        $this->crud->setEntityNameStrings('Contrato', 'Contratos');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Global
        |--------------------------------------------------------------------------
        */
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));

//        dd(session()->get('user_ug_id'));
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Collumns Table
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'name'  => 'numero',
            'label' => 'Número Contrato',
            'type'  => 'text',
        ]);

        $this->crud->addColumns([
            [
                'name'          => 'getUnidade',
                'label'         => 'Unidade Gestora', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
        ]);
        $this->crud->addColumns([
            [
                'name'          => 'getCategoria',
                'label'         => 'Categoria', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'getCategoria', // the method in your Model
                'orderable' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
        ]);


        $this->crud->addColumns([
            [
                'name'          => 'getFornecedor',
                'label'         => 'Fornecedor', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'getFornecedor', // the method in your Model
                'orderable' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
        ]);

        $this->crud->addColumn([
            'name'  => 'processo',
            'label' => 'Processo',
            'type'  => 'text',
        ]);

        $this->crud->addColumn([
            'name'  => 'objeto',
            'label' => 'Objeto',
            'type'  => 'text',
        ]);

        $this->crud->addColumn([
            'name'  => 'vigencia_inicio',
            'label' => 'Vig. Início',
            'type'  => 'date',
        ]);

        $this->crud->addColumn([
            'name'  => 'vigencia_fim',
            'label' => 'Vig. Fim',
            'type'  => 'date',
        ]);

        $this->crud->addColumns([
            [
                'name'          => 'formatVlrGlobal',
                'label'         => 'Valor Global', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'formatVlrGlobal', // the method in your Model
                'orderable' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
        ]);

        $this->crud->addColumn([
            'name'  => 'num_parcelas',
            'label' => 'Núm. Parcelas',
            'type'  => 'number',
        ]);

        $this->crud->addColumns([
            [
                'name'          => 'formatVlrParcela',
                'label'         => 'Valor Parcela', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'formatVlrParcela', // the method in your Model
                'orderable' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
        ]);


        $this->crud->addColumns([
            [
                'name'          => 'getSituacao',
                'label'         => 'Situação', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
        ]);

//        $this->crud->addColumn([
//            'name'  => 'cpf_cnpj_idgener',
//            'label' => 'CPF/CNPJ/UG/ID Genérico',
//            'type'  => 'text',
//        ]);
//
//        $this->crud->addColumn([
//            'name'  => 'nome',
//            'label' => 'Nome / Razão Social',
//            'type'  => 'text',
//        ]);

        // add asterisk for fields that are required in ContratoRequest


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Campos Formulário
        |--------------------------------------------------------------------------
        */


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
}
