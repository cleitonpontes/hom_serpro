<?php

namespace App\Http\Controllers\Gescon\Siasg;

use App\Models\Codigoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SiasgcompraRequest as StoreRequest;
use App\Http\Requests\SiasgcompraRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;

/**
 * Class SiasgcompraCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SiasgcompraCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Siasgcompra');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/siasg/compras');
        $this->crud->setEntityNameStrings('Compra - SIASG', 'Cadastro Compras - SIASG');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contrato_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contrato_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contrato_deletar')) ? $this->crud->allowAccess('delete') : null;

        $this->crud->enableExportButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->campos();
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in SiasgcompraRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function colunas()
    {
        return [
            [
                'name' => 'getUnidade',
                'label' => 'Unidade da Compra', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'ano',
                'label' => 'Ano Compra',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numero',
                'label' => 'Número Compra',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getModalidade',
                'label' => 'Modalidade Licitação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getModalidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'mensagem',
                'label' => 'Mensagem retorno',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
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
            ],
        ];
    }

    public function campos()
    {
        $modalidades = $this->buscaModalidades();

        return [
            [
                // select_from_array
                'name' => 'modalidade_id',
                'label' => "Modalidade Licitação",
                'type' => 'select2_from_array',
                'options' => $modalidades,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'ano',
                'label' => 'Ano Compra',
                'type' => 'anoquatrodigitos',
            ],
            [
                'name' => 'numero',
                'label' => 'Número Compra',
                'type' => 'numerocompra',
            ],
            [
                // 1-n relationship
                'label' => "Unidade da Compra", // Table column heading
                'type' => "select2_from_ajax",
                'name' => 'unidade_id', // the column that contains the ID of that connected entity
                'entity' => 'unidade', // the method that defines the relationship in your Model
                'attribute' => "codigo", // foreign key attribute that is shown to user
                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_unidade',
                'model' => "App\Models\Unidade", // foreign key model
                'data_source' => url("api/unidade"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a Unidade", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
            ],
            [   // Hidden
                'name' => 'situacao',
                'type' => 'hidden',
                'default' => 'Pendente',
            ],
        ];

    }

    private function buscaModalidades()
    {
        $modalidades = Codigoitem::select(DB::raw("CONCAT(descres,' - ',descricao) AS nome"), 'id')
            ->whereHas('codigo', function ($query) {
                $query->where('descricao', '=', 'Modalidade Licitação');
            })
            ->whereIn('descres', config('api-siasg.modalidade_licitacao'))
            ->orderBy('descres')
            ->pluck('nome', 'id')
            ->toArray();

        return $modalidades;
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
