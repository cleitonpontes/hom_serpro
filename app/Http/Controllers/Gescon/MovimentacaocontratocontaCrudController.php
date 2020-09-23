<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MovimentacaocontratocontaRequest as StoreRequest;
use App\Http\Requests\MovimentacaocontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class MovimentacaocontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MovimentacaocontratocontaCrudController extends CrudController
{
    public function setup()
    {
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Movimentacaocontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/movimentacaocontratoconta');
        $this->crud->setEntityNameStrings('movimentacaocontratoconta', 'movimentacaocontratocontas');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        // add asterisk for fields that are required in MovimentacaocontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function Colunas()
    {
        $colunas = [
            // [
            //     'name' => 'getTipoMovimentacao',
            //     'label' => 'Tipo', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'getTipoMovimentacao', // the method in your Model
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //         $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
            //     },
            // ],
            // [
            //     'name' => 'getTipoEncargo',
            //     'label' => 'Tipo', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'getTipoEncargo', // the method in your Model
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //         $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
            //     },
            // ],
            [
                'name'  => 'mes_competencia',
                'label' => 'Mês',
                'type'  => 'text',
            ],
            [
                'name'  => 'ano_competencia',
                'label' => 'Ano',
                'type'  => 'text',
            ],
            [
                'name'  => 'situacao_movimentacao',
                'label' => 'Situação da movimentação',
                'type'  => 'text',
            ],
            // [
            //     'name'  => 'proporcionalidade',
            //     'label' => 'Proporcionalidade',
            //     'type'  => 'text',
            // ],
            // [
            //     'name' => 'formatValor',
            //     'label' => 'Valor', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'formatValor', // the method in your Model
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            // ],
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
