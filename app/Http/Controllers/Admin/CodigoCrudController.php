<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CodigoRequest as StoreRequest;
use App\Http\Requests\CodigoRequest as UpdateRequest;

/**
 * Class CodigoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CodigoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Codigo');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/codigo');
        $this->crud->setEntityNameStrings('Códigos', 'Códigos');

        $this->crud->addClause('where', 'visivel', '=', '1');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('codigo_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('codigo_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('codigo_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();



        $this->crud->addColumn([
            'name'  => 'descricao',
            'label' => 'Descrição',
            'type'  => 'text',
        ]);

        $this->crud->addColumns([
            [
                'name'          => 'getVisivel',
                'label'         => 'Visível', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'getVisivel', // the method in your Model
            ],
        ]);

        $this->crud->addField([
            'name'  => 'descricao',
            'label' => 'Descrição',
            'type'  => 'text',
        ]);

        $this->crud->addField([ // select_from_array
            'name'            => 'visivel',
            'label'           => 'Visível',
            'type'            => 'select_from_array',
            'options'         => ['1' => 'Sim'],
            'allows_null'     => false,
            'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        // add asterisk for fields that are required in CodigoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->addButtonFromModelFunction('line', 'codigoItens', 'codigoItens', 'end');
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

        $this->crud->removeColumn('visivel');

        return $content;
    }
}
