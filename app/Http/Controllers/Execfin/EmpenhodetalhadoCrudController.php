<?php

namespace App\Http\Controllers\Execfin;

use App\Models\Empenho;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmpenhodetalhadoRequest as StoreRequest;
use App\Http\Requests\EmpenhodetalhadoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class EmpenhodetalhadoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmpenhodetalhadoCrudController extends CrudController
{
    public function setup()
    {

        $empenho_id = \Route::current()->parameter('empenho_id');

        $empenho = Empenho::where('id', '=', $empenho_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$empenho) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Empenhodetalhado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/execfin/empenho/' . $empenho_id . '/empenhodetalhado');
        $this->crud->setEntityNameStrings('Empenho detalhado', 'Empenho Detalhado');

        $this->crud->addClause('join', 'empenhos', 'empenhos.id', '=', 'empenhodetalhado.empenho_id');
//        $this->crud->addClause('join', 'naturezadespesa', 'naturezadespesa.id', '=', 'empenhodetalhado.naturezadespesa_id');
        $this->crud->addClause('join', 'naturezasubitem', 'naturezasubitem.id', '=', 'empenhodetalhado.naturezasubitem_id');
        $this->crud->addClause('where', 'empenho_id', '=', $empenho_id);
        $this->crud->addClause('select', 'empenhodetalhado.*');
        $this->crud->orderBy('naturezasubitem_id', 'asc');


        $this->crud->addButtonFromView('top', 'voltar', 'voltarempenho', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('empenhodetalhado_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('empenhodetalhado_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('empenhodetalhado_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        // add asterisk for fields that are required in EmpenhodetalhadoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getEmpenho',
                'label' => 'Empenho', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getEmpenho', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('empenhos.numero', 'like', "%$searchTerm%");
                },
            ],
            [
                'name' => 'getNaturezadespesa',
                'label' => 'ND', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNaturezadespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('naturezades.codigo', 'like', "%$searchTerm%");
//                    $query->orWhere('naturezasubitem.descricao', 'like', "%" . strtoupper($searchTerm) . "%");
//                },
            ],
            [
                'name' => 'getSubitem',
                'label' => 'Sub-Item', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSubitem', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('naturezasubitem.codigo', 'like', "%$searchTerm%");
                    $query->orWhere('naturezasubitem.descricao', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'formatVlrEmpaliquidar',
                'label' => 'Emp. a Liquidar', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmpaliquidar', // the method in your Model
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
//            [
//                'name' => 'formatVlraLiquidar',
//                'label' => 'a Liquidar', // Table column heading
//                'type' => 'model_function',
//                'function_name' => 'formatVlraLiquidar', // the method in your Model
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic'   => function ($query, $column, $searchTerm) {
////                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
////                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
////                },
//            ],
//            [
//                'name' => 'formatVlrLiquidado',
//                'label' => 'Liquidado', // Table column heading
//                'type' => 'model_function',
//                'function_name' => 'formatVlrLiquidado', // the method in your Model
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic'   => function ($query, $column, $searchTerm) {
////                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
////                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
////                },
//            ],
//            [
//                'name' => 'formatVlrPago',
//                'label' => 'Pago', // Table column heading
//                'type' => 'model_function',
//                'function_name' => 'formatVlrPago', // the method in your Model
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic'   => function ($query, $column, $searchTerm) {
////                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
////                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
////                },
//            ],
//            [
//                'name' => 'formatVlrRpInscrito',
//                'label' => 'RP Inscrito', // Table column heading
//                'type' => 'model_function',
//                'function_name' => 'formatVlrRpInscrito', // the method in your Model
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic'   => function ($query, $column, $searchTerm) {
////                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
////                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
////                },
//            ],
//            [
//                'name' => 'formatVlrRpaLiquidar',
//                'label' => 'RP a Liquidar', // Table column heading
//                'type' => 'model_function',
//                'function_name' => 'formatVlrRpaLiquidar', // the method in your Model
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic'   => function ($query, $column, $searchTerm) {
////                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
////                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
////                },
//            ],
//            [
//                'name' => 'formatVlrRpLiquidado',
//                'label' => 'RP Liquidado', // Table column heading
//                'type' => 'model_function',
//                'function_name' => 'formatVlrRpLiquidado', // the method in your Model
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic'   => function ($query, $column, $searchTerm) {
////                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
////                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
////                },
//            ],
//            [
//                'name' => 'formatVlrRpPago',
//                'label' => 'RP Pago', // Table column heading
//                'type' => 'model_function',
//                'function_name' => 'formatVlrRpPago', // the method in your Model
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic'   => function ($query, $column, $searchTerm) {
////                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
////                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
////                },
//            ],

        ];

        return $colunas;

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
