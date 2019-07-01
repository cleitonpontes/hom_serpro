<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ComunicaRequest as StoreRequest;
use App\Http\Requests\ComunicaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class ComunicaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ComunicaCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Comunica');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/comunica');
        $this->crud->setEntityNameStrings('Comunica', 'Comunica');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('comunica_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('comunica_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('comunica_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $unidades = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'id')
            ->toArray();

        $grupos = Role::pluck('name', 'id')
            ->toArray();

        $campos = $this->Campos($unidades, $grupos);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ComunicaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getGrupo',
                'label' => 'Grupo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getGrupo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'assunto',
                'label' => 'Assunto', // Table column heading
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],

            [
                'name' => 'getMensagem',
                'label' => 'Mensagem', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getMensagem', // the method in your Model
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
//            [
//                'name' => 'mensagem',
//                'label' => 'Mensagem', // Table column heading
//                'type' => 'text',
//                'limit' => 1000,
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
//            ],
            [
                'name' => 'anexos',
                'label' => 'Anexos', // Table column heading
                'type' => 'upload_multiple',
                'disk' => 'local',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],


        ];

        return $colunas;

    }

    public function Campos($unidades, $grupos)
    {

        $campos = [
            [ // select_from_array
                'name' => 'unidade_id',
                'label' => "Unidade",
                'type' => 'select2_from_array',
                'options' => $unidades,
                'placeholder' => "Todas",
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [ // select_from_array
                'name' => 'role_id',
                'label' => "Grupos",
                'type' => 'select2_from_array',
                'options' => $grupos,
                'placeholder' => "Todos",
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [ // select_from_array
                'name' => 'assunto',
                'label' => "Assunto",
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [ // select_from_array
                'name' => 'mensagem',
                'label' => "Mensagem",
                'type' => 'ckeditor',
            ],
            [   // Upload
                'name' => 'anexos',
                'label' => 'Anexos',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'local'
                // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => ['E' => 'Enviado', 'I' => 'Inacabado', 'P' => 'Pronta para Envio'],
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

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'unidade_id',
            'role_id',
            'mensagem',
            'situacao',
        ]);

        return $content;
    }
}
