<?php

namespace App\Http\Controllers\Empenho;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MinutaEmpenhoRequest as StoreRequest;
use App\Http\Requests\MinutaEmpenhoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class MinutaEmpenhoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MinutaEmpenhoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MinutaEmpenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/minuta');
        $this->crud->setEntityNameStrings('Minuta de Empenho', 'Minutas de Empenho');

        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('show');

        $this->crud->addButton('', 'Nova Minuta', 'view', 'teste', '');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in MinutaEmpenhoRequest
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
