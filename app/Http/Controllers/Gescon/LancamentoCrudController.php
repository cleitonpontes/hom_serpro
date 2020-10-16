<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LancamentoRequest as StoreRequest;
use App\Http\Requests\LancamentoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\Movimentacaocontratoconta;


/**
 * Class LancamentoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LancamentoCrudController extends CrudController
{

    public function setup()
    {
        $movimentacaocontratoconta_id = \Route::current()->parameter('movimentacaocontratoconta_id');

        $objMovimentacaoContratoConta = Movimentacaocontratoconta::where('id', '=', $movimentacaocontratoconta_id)->first();
        $contratoconta_id = $objMovimentacaoContratoConta->contratoconta_id;


        \Route::current()->setParameter('contratoconta_id', $contratoconta_id);


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Lancamento');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/movimentacaocontratoconta/' . $movimentacaocontratoconta_id . '/lancamento');
        $this->crud->setEntityNameStrings('lancamento', 'lancamentos');

        // adicionar cláusula para trabalharmos apenas com lançamentos da movimentação
        $this->crud->addClause('select', 'lancamentos.*', 'codigoitens.descricao');
        $this->crud->addClause('join', 'movimentacaocontratocontas', 'movimentacaocontratocontas.id',  '=',  'lancamentos.movimentacao_id');
        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id',  '=',  'movimentacaocontratocontas.tipo_id');
        $this->crud->addClause('where', 'lancamentos.movimentacao_id', '=', $movimentacaocontratoconta_id);


        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        // $this->crud->denyAccess('show');

        $this->crud->addButtonFromView('top', 'voltarparamovimentacoes', 'voltarparamovimentacoes', 'end');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        // add asterisk for fields that are required in LancamentoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getNomePessoaContratoTerceirizado',
                'label' => 'Terceirizado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNomePessoaContratoTerceirizado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name' => 'getSalarioContratoTerceirizado',
                'label' => 'Salário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSalarioContratoTerceirizado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name' => 'getTipoEncargo',
                'label' => 'Verba', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoEncargo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                },
            ],
            [
                'name' => 'getPercentualEncargo',
                'label' => 'Percentual', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getPercentualEncargo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                },
            ],
            // [
            //     'name'  => 'mes_competencia',
            //     'label' => 'Mês',
            //     'type'  => 'text',
            // ],
            // [
            //     'name'  => 'ano_competencia',
            //     'label' => 'Ano',
            //     'type'  => 'text',
            // ],
            // [
            //     'name'  => 'situacao_movimentacao',
            //     'label' => 'Situação da movimentação',
            //     'type'  => 'text',
            // ],
            // [
            //     'name'  => 'proporcionalidade',
            //     'label' => 'Proporcionalidade',
            //     'type'  => 'text',
            // ],

            [
                'name'  => 'descricao',
                'label' => 'Tipo da movimentação',
                'type'  => 'text',
            ],



            [
                'name' => 'formatValor',
                'label' => 'Valor lançamento', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatValor', // the method in your Model
                'prefix' => "R$ ",
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
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
