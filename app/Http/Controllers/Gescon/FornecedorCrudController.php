<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\FornecedorRequest as StoreRequest;
use App\Http\Requests\FornecedorRequest as UpdateRequest;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FornecedorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FornecedorCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Fornecedor');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/fornecedor');
        $this->crud->setEntityNameStrings('fornecedor', 'fornecedores');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('fornecedor_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('fornecedor_editar')) ? $this->crud->allowAccess('update') : null;
//        (backpack_user()->can('fornecedor_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumns([
            [
                'name'          => 'getTipo',
                'label'         => 'Tipo Fornecedor', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('', 'like', '%'.$searchTerm.'%');
//                },
            ],
        ]);

        $this->crud->addColumn([
            'name'  => 'cpf_cnpj_idgener',
            'label' => 'CPF/CNPJ/UG/ID Genérico',
            'type'  => 'text',
        ]);

        $this->crud->addColumn([
            'name'  => 'nome',
            'limit' => 1000,
            'label' => 'Nome / Razão Social',
            'type'  => 'text',
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('nome', 'like', "%" . strtoupper($searchTerm) . "%");
            },
        ]);

        $tipo_fornecedor = Codigoitem::whereHas('codigo', function ($q){
            $q->where('descricao', '=', 'Tipo Fornecedor');
        })->pluck('descricao', 'descres')->toArray();

        $this->crud->addField([ // select_from_array
            'name'            => 'tipo_fornecedor',
            'label'           => 'Tipo Fornecedor',
            'type'            => 'select_from_array',
            'attributes' => [
                'id' => 'tipo_fornecedor',
            ],
            'options'         => $tipo_fornecedor,
            'allows_null'     => true,
            'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        $this->crud->addField([
            'name'  => 'cpf_cnpj_idgener',
            'label' => 'CPF/CNPJ/UG/ID Genérico',
            'type'  => 'cpf_cnpj_idgener',
        ]);

        $this->crud->addField([
            'name'  => 'nome',
            'label' => 'Nome',
            'type'  => 'text',
            'attributes' => [
                'onkeyup' => "maiuscula(this)"
            ]
        ]);

//        $this->crud->addField([ // select_from_array
//            'name'            => 'visivel',
//            'label'           => 'Visível',
//            'type'            => 'select_from_array',
//            'options'         => ['1' => 'Sim'],
//            'allows_null'     => false,
//            'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
//        ]);
        $this->crud->enableAjaxTable();
        // add asterisk for fields that are required in FornecedorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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

        $this->crud->removeColumn('tipo_fornecedor');

        return $content;
    }
}
