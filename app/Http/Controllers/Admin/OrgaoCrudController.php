<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OrgaoRequest as StoreRequest;
use App\Http\Requests\OrgaoRequest as UpdateRequest;

/**
 * Class OrgaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrgaoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Orgao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/orgao');
        $this->crud->setEntityNameStrings('orgao', 'orgaos');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $this->crud->setColumns(['codigo', 'codigosiasg', 'nome', 'orgaosuperior_id','situacao']);
        $this->crud->addField([
            'name' => 'codigo',
            'type' => 'number',
            'label' => "Código SIAFI"
        ]);
        $this->crud->addField([
            'name' => 'codigosiasg',
            'type' => 'number',
            'label' => "Código SIASG"
        ]);
        $this->crud->addField([
            'name' => 'nome',
            'type' => 'text',
            'label' => "Nome"
        ]);
        $this->crud->addField([  // Select
            'label' => "Órgão Superior",
            'type' => 'select2',
            'name' => 'orgaosuperior_id', // the db column for the foreign key
            'entity' => 'orgaosuperior', // the method that defines the relationship in your Model
            'attribute' => 'nome', // foreign key attribute that is shown to user
            'model' => "App\Models\Orgaosuperior" // foreign key model
        ]);

        $this->crud->addField([
            'name' => 'situacao',
            'label' => "Situação",
            'type' => 'select_from_array',
            'options' => ['A' => 'Ativo', 'I' => 'Inativo'],
            'allows_null' => false,
            'default' => 'A',
        ]);


        // add asterisk for fields that are required in OrgaoRequest
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
