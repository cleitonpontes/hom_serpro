<?php

namespace App\Http\Controllers\Execfin;

use App\Models\Execsfsituacao;
use App\Models\Naturezasubitem;
use App\Models\Rhrubrica;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RhsituacaoRequest as StoreRequest;
use App\Http\Requests\RhsituacaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class RhsituacaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RhsituacaoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Rhsituacao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/execfin/rhsituacao');
        $this->crud->setEntityNameStrings('RH - Situação', 'RH - Situações');
        $this->crud->addClause('join', 'execsfsituacao', 'execsfsituacao.id', '=', 'rhsituacao.execsfsituacao_id');
        $this->crud->addClause('select', 'rhsituacao.*');


        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('rhsituacao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('rhsituacao_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('rhsituacao_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $dados = new Naturezasubitem();
        $nddetalhada = $dados->retornaNdDetalhada();

        $execsfsituacao = Execsfsituacao::select(DB::raw("CONCAT(codigo,' - ',descricao) AS nome"), 'id')
            ->where('status','=','true')
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'id')
            ->toArray();


        $campos = $this->Campos($execsfsituacao,$nddetalhada);
        $this->crud->addFields($campos);


        // add asterisk for fields that are required in RhsituacaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getExecsfsituacao',
                'label' => 'Situação Siafi', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getExecsfsituacao', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic'   => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('execsfsituacao.codigo', 'like', strtoupper("%$searchTerm%"));
                    $query->orWhere('execsfsituacao.descricao', 'like', strtoupper("%$searchTerm%"));
                },
            ],
            [
                'name' => 'nd',
                'label' => 'ND Detalhada',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vpd',
                'label' => 'VPD',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'ddp_nivel',
                'label' => 'DDP Nível',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
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
            [ // n-n relationship (with pivot table)
                'name'      => 'rhrubricas',
                'label'     => 'Rubricas',
                'type'      => 'select_multiple',
                'entity'    => 'rhrubricas',
                'attribute' => 'codigo',
                'model'     => Rhrubrica::class,
                'pivot'     => true,
            ],

        ];

        return $colunas;

    }

    public function Campos($execsfsituacao,$nddetalhada)
    {

        $campos = [
            [ // select2_from_array
                'name' => 'execsfsituacao_id',
                'label' => 'Situação Siafi',
                'type' => 'select2_from_array',
                'options' => $execsfsituacao,
                'allows_null' => true,
                'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select2_from_array
                'name' => 'nd',
                'label' => 'ND Detalhada',
                'type' => 'select2_from_array',
                'options' => $nddetalhada,
                'allows_null' => true,
                'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'vpd',
                'label' => "VPD",
                'type' => 'vpd',
            ],
            [ // select_from_array
                'name' => 'ddp_nivel',
                'label' => "DDP Nível",
                'type' => 'select_from_array',
                'options' => config('app.ddp_nivel'),
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'status',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
            ],
            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => "Rubricas",
                'type' => 'select2_multiple',
                'name' => 'rhrubricas', // the method that defines the relationship in your Model
                'entity' => 'rhrubricas', // the method that defines the relationship in your Model
                'attribute' => 'codigo', // foreign key attribute that is shown to user
                'model' => "App\Models\Rhrubrica", // foreign key model
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'options' => (function ($query) {
                    return $query->orderBy('codigo', 'ASC')->get();
                }),
                // 'select_all' => true, // show Select All and Clear buttons?
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
