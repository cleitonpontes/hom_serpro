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
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos();
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in RhrubricaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'codigo',
                'label' => 'Código',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'descricao',
                'label' => 'Descrição',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'criacao',
                'label' => 'Criação',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'tipo',
                'label' => 'Tipo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
            ],
        ];

        return $colunas;

    }

    public function Campos()
    {

        $campos = [
            [ // select_from_array
                'name' => 'codigo',
                'label' => "Código",
                'type' => 'codigorubrica',
                'attributes' => [
                    'autofocus' => "autofocus",
                ]
            ],
            [ // select_from_array
                'name' => 'descricao',
                'label' => "Descrição",
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [ // select_from_array
                'name' => 'criacao',
                'label' => "Criação (MM/AAAA)",
                'type' => 'criacaorubrica',

            ],
            [ // select_from_array
                'name' => 'tipo',
                'label' => "Tipo",
                'type' => 'select_from_array',
                'options' => config('app.tipo_rubrica'),
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => config('app.situacao_rubrica'),
                'allows_null' => true,
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
