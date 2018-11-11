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
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/unidade');
        $this->crud->setEntityNameStrings('unidade', 'unidades');

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
            'label' => "Código SIAFI",
            'tab' => "Dados Gerais"
        ]);
        $this->crud->addField([
            'name' => 'codigosiasg',
            'type' => 'number',
            'label' => "Código SIASG",
            'tab' => "Dados Gerais"
        ]);
        $this->crud->addField([
            'name' => 'nome',
            'type' => 'text',
            'label' => "Nome",
            'tab' => "Dados Gerais"
        ]);
        $this->crud->addField([
            'name' => 'nomeresumido',
            'type' => 'text',
            'label' => "Nome Resumido",
            'tab' => "Dados Gerais"
        ]);
        $this->crud->addField([
            'name' => 'telefone',
            'type' => 'text',
            'label' => "Telefone",
            'tab' => "Dados Gerais"
        ]);

        $this->crud->addField([
            'name' => 'tipo',
            'label' => "Tipo",
            'type' => 'select_from_array',
            'options' => ['UC' => 'Unidade Controle', 'UGE' => 'Unidade Gestora Executora'],
            'allows_null' => true,
            'tab' => "Outros Dados"
        ]);

        $this->crud->addField([  // Select
            'label' => "Órgão",
            'type' => 'select2',
            'name' => 'orgao_id', // the db column for the foreign key
            'entity' => 'orgao', // the method that defines the relationship in your Model
            'attribute' => 'nome', // foreign key attribute that is shown to user
            'model' => "App\Models\Orgao", // foreign key model
            'tab' => "Outros Dados"
        ]);

        $this->crud->addField([
            'name' => 'situacao',
            'label' => "Situação",
            'type' => 'select_from_array',
            'options' => ['A' => 'Ativo', 'I' => 'Inativo'],
            'allows_null' => false,
            'default' => 'A',
            'tab' => "Outros Dados"
        ]);

        // add asterisk for fields that are required in UnidadeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();
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
