<?php

namespace App\Http\Controllers\Execfin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RhrubricaRequest as StoreRequest;
use App\Http\Requests\RhrubricaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class RhrubricaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RhrubricaCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Rhrubrica');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/execfin/rhrubrica');
        $this->crud->setEntityNameStrings('RH - Rubrica', 'RH - Rubricas');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('rhrubrica_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('rhrubrica_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('rhrubrica_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in RhrubricaRequest
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
