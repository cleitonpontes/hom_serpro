<?php

namespace App\Http\Controllers\Execfin;

use App\Models\Empenho;
use App\Models\Naturezasubitem;
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

        $naturezasubitem = Naturezasubitem::select(DB::raw("CONCAT(codigo,' - ',descricao) AS nome"), 'id')
            ->where('naturezadespesa_id', '=', $empenho->naturezadespesa_id)
            ->where('codigo', '<>', '00')
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'id')->toArray();

//        dd();

        $emp = $empenho->where('id','=', $empenho_id)->pluck('numero', 'id')->toArray();

        $campos = $this->Campos($emp, $naturezasubitem);

        $this->crud->addFields($campos);

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
                'label' => 'Natureza Despesa (ND)', // Table column heading
                'type' => 'model_function',
                'limit' => 1000,
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
                'label' => 'Subitem', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSubitem', // the method in your Model
                'limit' => 1000,
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
            ],
            [
                'name' => 'formatVlrEmpemliquidacao',
                'label' => 'Emp. em Liquidação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmpemliquidacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrEmpliquidado',
                'label' => 'Emp. Liquidado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmpliquidado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrEmppago',
                'label' => 'Emp. Pago', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmppago', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrEmpaliqrpnp',
                'label' => 'Emp. a Liquidar RPNP', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmpaliqrpnp', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrEmpemliqrpnp',
                'label' => 'Emp. em Liquidação RPNP', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmpemliqrpnp', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrEmprpp',
                'label' => 'Emp. RPP', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmprpp', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpaliquidar',
                'label' => 'RPNP a Liquidar', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpaliquidar', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpaliquidaremliquidacao',
                'label' => 'RPNP a Liqu. em Liquidação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpaliquidaremliquidacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpliquidado',
                'label' => 'RPNP Liquidado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpliquidado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnppago',
                'label' => 'RPNP Pago', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnppago', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpaliquidarbloq',
                'label' => 'RPNP a Liq. Bloqueado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpaliquidarbloq', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpaliquidaremliquidbloq',
                'label' => 'RPNP a Liq. em Liquid. Bloqueado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpaliquidaremliquidbloq', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpcancelado',
                'label' => 'RPNP Cancelado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpcancelado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpoutrocancelamento',
                'label' => 'RPNP Outros Cancelamentos', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpoutrocancelamento', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpnpemliqoutrocancelamento',
                'label' => 'RPNP em Liq. Out. Cancelam.', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpnpemliqoutrocancelamento', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRppliquidado',
                'label' => 'RPP Liquidado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRppliquidado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRpppago',
                'label' => 'RPP Pago', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpppago', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRppcancelado',
                'label' => 'RPP Cancelado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRppcancelado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],

        ];

        return $colunas;

    }

    public function Campos($empenho, $naturezasubitem)
    {

        $campos = [
            [ // select_from_array
                'name' => 'empenho_id',
                'label' => "Empenho",
                'type' => 'select2_from_array',
                'options' => $empenho,
                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'naturezasubitem_id',
                'label' => "Subitem",
                'type' => 'select2_from_array',
                'options' => $naturezasubitem,
                'allows_null' => true,
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

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('empenho_id');
        $this->crud->removeColumn('naturezasubitem_id');
        $this->crud->removeColumn('empaliquidar');
        $this->crud->removeColumn('empemliquidacao');
        $this->crud->removeColumn('empliquidado');
        $this->crud->removeColumn('emppago');
        $this->crud->removeColumn('empaliqrpnp');
        $this->crud->removeColumn('empemliqrpnp');
        $this->crud->removeColumn('emprpp');
        $this->crud->removeColumn('rpnpaliquidar');
        $this->crud->removeColumn('rpnpaliquidaremliquidacao');
        $this->crud->removeColumn('rpnpliquidado');
        $this->crud->removeColumn('rpnppago');
        $this->crud->removeColumn('rpnpaliquidarbloq');
        $this->crud->removeColumn('rpnpaliquidaremliquidbloq');
        $this->crud->removeColumn('rpnpcancelado');
        $this->crud->removeColumn('rpnpoutrocancelamento');
        $this->crud->removeColumn('rpnpemliqoutrocancelamento');
        $this->crud->removeColumn('rppliquidado');
        $this->crud->removeColumn('rpppago');
        $this->crud->removeColumn('rppcancelado');

        return $content;
    }
}
