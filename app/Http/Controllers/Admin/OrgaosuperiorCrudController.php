<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OrgaosuperiorRequest as StoreRequest;
use App\Http\Requests\OrgaosuperiorRequest as UpdateRequest;
use Illuminate\Auth\Access\Gate;

/**
 * Class OrgaosuperiorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrgaosuperiorCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Orgaosuperior');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/orgaosuperior');
        $this->crud->setEntityNameStrings('orgaosuperior', 'orgaosuperiors');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in OrgaosuperiorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function index(){
        if(\Illuminate\Support\Facades\Gate::denies('administracao_orgaosuperior_acesso')){
            \Alert::warning('Sem Permissão!')->flash();

            return redirect('sc/inicio');
        }
        return parent::index();
    }

    public function show($id){
        if(\Illuminate\Support\Facades\Gate::denies('administracao_orgaosuperior_mostrar')){
            \Alert::warning('Sem Permissão!')->flash();

            return redirect('sc/inicio');
        }
        return parent::show($id);
    }

    public function create(){
        if(\Illuminate\Support\Facades\Gate::denies('administracao_orgaosuperior_inserir')){
            \Alert::warning('Sem Permissão!')->flash();

            return redirect('sc/inicio');
        }
        return parent::create();
    }

    public function edit($id){
        if(\Illuminate\Support\Facades\Gate::denies('administracao_orgaosuperior_editar')){
            \Alert::warning('Sem Permissão!')->flash();

            return redirect('sc/inicio');
        }
        return parent::edit($id);
    }

    public function destroy($id){
        if(\Illuminate\Support\Facades\Gate::denies('administracao_orgaosuperior_excluir')){
            \Alert::warning('Sem Permissão!')->flash();

            return redirect('sc/inicio');
        }
        return parent::destroy($id);
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
