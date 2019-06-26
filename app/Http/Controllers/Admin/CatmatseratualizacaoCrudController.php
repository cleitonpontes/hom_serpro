<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CatmatseratualizacaoRequest as StoreRequest;
use App\Http\Requests\CatmatseratualizacaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class CatmatseratualizacaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CatmatseratualizacaoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Catmatseratualizacao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/catmatseratualizacao');
        $this->crud->setEntityNameStrings('Atualização CatMatSer', 'Atualizações CatMatSer');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('atualizacaocatmatser_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('atualizacaocatmatser_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('atualizacaocatmatser_deletar')) ? $this->crud->allowAccess('delete') : null;

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

        // add asterisk for fields that are required in CatmatseratualizacaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'arquivo',
                'label' => 'Arquivo',
                'type' => 'upload',
                'disk' => 'local',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
            ],
            [
                'name' => 'getSituacaoCatMatSerAtualizacao',
                'label' => 'Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacaoCatMatSerAtualizacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'created_at',
                'label' => 'Criado em', // Table column heading
                'type' => 'datetime',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'updated_at',
                'label' => 'Atualizado em', // Table column heading
                'type' => 'datetime',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;
    }

    public function Campos()
    {

        $campos = [
            [   // Upload
                'name' => 'arquivo',
                'label' => 'Arquivo',
                'type' => 'upload',
                'upload' => true,
                'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [
                    'P' => 'Pendente',
                    'L' => 'Lido',
                    'E' => 'Erro',
                ],
                'default' => 'P',
                'allows_null' => false,
                'attributes' => [
                    'readonly'=>'readonly',
                    'style' => 'pointer-events: none;touch-action: none;'
                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
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
