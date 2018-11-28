<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\Authorizes;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UsuarioRequest as StoreRequest;
use App\Http\Requests\UsuarioRequest as UpdateRequest;

/**
 * Class UsuarioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UsuarioCrudController extends CrudController
{
    use Authorizes;

    public function setup()
    {


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BackpackUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/usuario');
        $this->crud->setEntityNameStrings('usuario', 'usuarios');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->enableExportButtons();
        $this->crud->setFromDb();

        // add asterisk for fields that are required in UsuarioRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();

    }

    public function store(StoreRequest $request)
    {
        $this->authorizePermissions(['administracao_editar_usuario']);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $this->authorizePermissions(['administracao_editar_usuario']);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
