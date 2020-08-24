<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoarquivoRequest as StoreRequest;
use App\Http\Requests\ContratoarquivoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ContratoarquivoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoarquivoCrudController extends CrudController
{
    public function setup()
    {

        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratoarquivo');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/' . $contrato_id . '/arquivos');
        $this->crud->setEntityNameStrings('Arquivo do Contrato', 'Arquivos - Contrato');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contratoarquivo_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratoarquivo_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratoarquivo_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $num_processo = $contrato->processo;

        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Arquivos Contrato');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $campos = $this->Campos($con, $tipos, $num_processo);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in ContratoarquivoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Número do instrumento', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'processo',
                'label' => 'Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'sequencial_documento',
                'label' => 'Nº SEI / Chave Acesso Sapiens',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'descricao',
                'label' => 'Descrição', // Table column heading
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'disk' => 'local',
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

    public function Campos($con, $tipos, $num_processo)
    {

        $campos = [
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Número do instrumento",
                'type' => 'select_from_array',
                'options' => $con,
                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'tipo',
                'label' => "Tipo",
                'type' => 'select2_from_array',
                'options' => $tipos,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'processo',
                'label' => 'Número Processo',
                'type' => 'numprocesso',
                'default' => $num_processo
            ],
            [   // Number
                'name' => 'sequencial_documento',
                'label' => 'Nº SEI / Chave Acesso Sapiens',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "minusculo(this)"
                ]
            ],
            [   // Number
                'name' => 'descricao',
                'label' => 'Descrição',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
//                'attributes' => [
//                    'id' => 'valor',
//                ],
//                'prefix' => "R$",
                // optionals

                // 'suffix' => ".00",
            ],
            [   // Upload
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
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
