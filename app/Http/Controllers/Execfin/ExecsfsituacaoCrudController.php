<?php

namespace App\Http\Controllers\Execfin;

use App\Models\Execsfsituacao;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ExecsfsituacaoRequest as StoreRequest;
use App\Http\Requests\ExecsfsituacaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;

/**
 * Class ExecsfsituacaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ExecsfsituacaoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Execsfsituacao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/execfin/situacaosiafi');
        $this->crud->setEntityNameStrings('Situação Siafi', 'Situações Siafi');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('situacaosiafi_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('situacaosiafi_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('situacaosiafi_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $execsfsituacao = Execsfsituacao::select(DB::raw("CONCAT(codigo,' - ',descricao) AS nome"), 'id')
            ->where('status','=','true')
            ->where('aba','<>','DESPESA_ANULAR')
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'id')
            ->toArray();


        $campos = $this->Campos($execsfsituacao);

        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ExecsfsituacaoRequest
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
                'name' => 'getExecsfsituacao',
                'label' => 'Anula Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getExecsfsituacao', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'aba',
                'label' => 'Aba',
                'type' => 'select_from_array',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => config('app.abas')
            ],
            [
                'name' => 'status',
                'label' => 'Situação',
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

    public function Campos($execsfsituacao)
    {

        $campos = [
            [ // select_from_array
                'name' => 'codigo',
                'label' => "Código",
                'type' => 'codigosituacaosiafi',
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
                'name' => 'execsfsituacao_id',
                'label' => "Anula Situação",
                'type' => 'select2_from_array',
                'options' => $execsfsituacao,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'aba',
                'label' => "Aba",
                'type' => 'select_from_array',
                'options' => config('app.abas'),
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'status',
                'label' => "Situação",
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

        $request->request->set(
            'codigo',
            strtoupper($request->input('codigo'))
        );

        $request->request->set(
            'descricao',
            strtoupper($request->input('descricao'))
        );

        $request->request->set(
            'categoria_ddp',
            array_search($request->input('aba'), config('app.aba_x_categoria_ddp'))
        );

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $request->request->set(
            'codigo',
            strtoupper($request->input('codigo'))
        );

        $request->request->set(
            'descricao',
            strtoupper($request->input('descricao'))
        );

        $request->request->set(
            'categoria_ddp',
            array_search($request->input('aba'), config('app.aba_x_categoria_ddp'))
        );
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('execsfsituacao_id');

        return $content;
    }
}
