<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TipolistafaturaRequest as StoreRequest;
use App\Http\Requests\TipolistafaturaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class TipolistafaturaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TipolistafaturaCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Tipolistafatura');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/tipolistafatura');
        $this->crud->setEntityNameStrings('Tipo de Lista Fatura', 'Tipo de Listas Fatura');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('tipolistafatura_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('tipolistafatura_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('tipolistafatura_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos();

        $this->crud->addFields($campos);

        // add asterisk for fields that are required in TipolistafaturaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'nome',
                'label' => 'Nome',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'situacao',
                'label' => 'Situa????o',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],

        ];

        return $colunas;

    }

    public function Campos()
    {

        $campos = [
            [ // select_from_array
                'name' => 'nome',
                'label' => "Nome",
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situa????o",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
            ],

        ];

        return $campos;
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
