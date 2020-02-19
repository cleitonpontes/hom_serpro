<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contratohistorico;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SaldohistoricoitemRequest as StoreRequest;
use App\Http\Requests\SaldohistoricoitemRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SaldohistoricoitemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SaldohistoricoitemCrudController extends CrudController
{
    public function setup()
    {
        $contratohistorico_id = \Route::current()->parameter('contratohistorico_id');

        $contratohistorico = Contratohistorico::where('id', '=', $contratohistorico_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contratohistorico) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Saldohistoricoitem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contratohistorico/' . $contratohistorico_id . '/itens');
        $this->crud->setEntityNameStrings('Contrato Histórico Item', 'Contrato Histórico Itens');
        $this->crud->addClause('where', 'saldoable_type', '=', 'App\Models\Contratohistorico');
        $this->crud->addClause('where', 'saldoable_id', '=', $contratohistorico_id);

        $this->crud->addButtonFromView('top', 'voltar', 'voltar', 'end');

        (backpack_user()->can('saldohistoricoitens_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('saldohistoricoitens_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('saldohistoricoitens_deletar')) ? $this->crud->allowAccess('delete') : null;


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        // add asterisk for fields that are required in SaldohistoricoitemRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
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
                'name' => 'getTipoItem',
                'label' => 'Tipo Item', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoItem', // the method in your Model
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
                'name' => 'getContratoItem',
                'label' => 'Item', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContratoItem', // the method in your Model
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
                'name' => 'getDescricaoComplementar',
                'label' => 'Descriçao Complementar',
                'type' => 'model_function',
                'function_name' => 'getDescricaoComplementar', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'quantidade',
                'label' => 'Quantidade', // Table column heading
                'type' => 'number',
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
                'name' => 'formatValorUnitarioItem',
                'label' => 'Valor Unitário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorUnitarioItem', // the method in your Model
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
                'name' => 'formatValorTotalItem',
                'label' => 'Valor Total', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValorTotalItem', // the method in your Model
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
