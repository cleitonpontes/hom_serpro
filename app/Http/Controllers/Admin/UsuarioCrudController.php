<?php

namespace App\Http\Controllers\Admin;

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
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\User');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/usuario');
        $this->crud->setEntityNameStrings('usuario', 'usuarios');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //colunar table
        $this->crud->addColumn([
            'name' => 'cpf', // The db column name
            'label' => "CPF", // Table column heading
            'type' => 'cpf'
        ]);
        $this->crud->addColumn([
            'name' => 'name', // The db column name
            'label' => "Nome", // Table column heading
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'email', // The db column name
            'label' => "E-mail", // Table column heading
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'ugprimaria', // The db column name
            'label' => "UG Primária", // Table column heading
            'type' => 'text'
        ]);


        //campos formularios
        $this->crud->addField([
            'name' => 'cpf',
            'type' => 'cpf',
            'label' => "CPF",
            'tab' => "Dados Pessoais"
        ]);
        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => "Nome",
            'tab' => "Dados Pessoais"
        ]);

        $this->crud->addField([
            'name' => 'email',
            'type' => 'email',
            'label' => "E-mail",
            'tab' => "Dados Pessoais"
        ]);

        $this->crud->addField([
            'name' => 'ugprimaria',
            'type' => 'ug',
            'label' => "UG Primária",
            'tab' => "Outras Informações"
        ]);

        $this->crud->addField([
            'label' => "UG Secundária",
            'type' => 'select2_multiple',
            'name' => 'unidades', // the method that defines the relationship in your Model
            'entity' => 'unidades', // the method that defines the relationship in your Model
            'attribute' => 'nome', // foreign key attribute that is shown to user
            'model' => "App\Models\Unidade", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'select_all' => true, // show Select All and Clear buttons?
            'tab' => "Outras Informações"
        ]);

        $this->crud->addField([
            'label' => "Grupos",
            'type' => 'select2_multiple',
            'name' => 'roles', // the method that defines the relationship in your Model
            'entity' => 'roles', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Role", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'select_all' => true, // show Select All and Clear buttons?
            'tab' => "Outras Informações"
        ]);


        // add asterisk for fields that are required in UsuarioRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $cpf = explode('.', $request->input('cpf'));
       $request->request->set('password', bcrypt($cpf[0].$cpf[1]));

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
