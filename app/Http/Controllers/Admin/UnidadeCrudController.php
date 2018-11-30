<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UnidadeRequest as StoreRequest;
use App\Http\Requests\UnidadeRequest as UpdateRequest;

/**
 * Class UnidadeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UnidadeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Unidade');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/unidade');
        $this->crud->setEntityNameStrings('Unidades', 'Unidades');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        $this->crud->addColumn([ // n-n relationship (with pivot table)
            'label'     => 'Cód. Órgão',
            'type'      => 'select',
            'name'      => 'orgao',
            'entity'    => 'orgao',
            'attribute' => 'codigo',
            'model'     => 'App\Models\Orgao',
            'pivot'     => true,
        ]);
        $this->crud->addColumns([
            [
                'name'          => 'getCodigoNome',
                'label'         => 'Unidade Gestora', // Table column heading
                'type'          => 'model_function',
                'function_name' => 'getCodigoNome', // the method in your Model
                'searchLogic'   => function ($query, $column, $searchTerm) {
                    $query->orWhere('codigo', 'like', '%'.$searchTerm.'%');
                    $query->orWhere('nomeresumido', 'like', '%'.$searchTerm.'%');
                },
            ],
        ]);

        $this->crud->addColumn([
            'name'  => 'telefone',
            'label' => 'Telefone',
            'type'  => 'text',
        ]);
        $this->crud->addColumn([
            'name'  => 'telefone',
            'label' => 'Telefone',
            'type'  => 'text',
        ]);

        $this->crud->addButtonFromModelFunction('line', 'more', 'moreOptions', 'beginning');
//        $this->crud->addButtonFromView('line', 'morecodigoitem', 'morecodigoitem', 'end');

        $this->crud->enableExportButtons();
        $this->crud->allowAccess(['list', 'delete', 'show']);
        // add asterisk for fields that are required in UnidadeRequest
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
}
