<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ExtratocontratocontaRequest as StoreRequest;
use App\Http\Requests\ExtratocontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ExtratocontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ExtratocontratocontaCrudController extends CrudController
{
    public function setup()
    {


        $contratoconta_id = \Route::current()->parameter('contratoconta_id');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Extratocontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/extratocontratoconta');
        $this->crud->setEntityNameStrings('extratocontratoconta', 'Extrato da Conta');

        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('show');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();


        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        // add asterisk for fields that are required in ExtratocontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');



        // cláusulas para possibilitar buscas
        // $this->crud->addClause('select', 'lancamentos.*');
        $this->crud->addClause('select', 'lancamentos.*', 'contratoterceirizados.*', 'c1.descricao as nome_encargo', 'c2.descricao as nome_movimentacao');
        $this->crud->addClause('join', 'movimentacaocontratocontas', 'movimentacaocontratocontas.id',  '=',  'lancamentos.movimentacao_id');
        $this->crud->addClause('join', 'contratoterceirizados', 'contratoterceirizados.id',  '=',  'lancamentos.contratoterceirizado_id');
        $this->crud->addClause('join', 'encargos', 'encargos.id',  '=',  'lancamentos.encargo_id');
        $this->crud->addClause('join', 'codigoitens as c1', 'c1.id',  '=',  'encargos.tipo_id');

        $this->crud->addClause('join', 'codigoitens as c2', 'c2.id',  '=',  'movimentacaocontratocontas.tipo_id');



        $this->crud->addClause('where', 'movimentacaocontratocontas.contratoconta_id', '=', $contratoconta_id);

        $this->crud->addClause('orderby', 'lancamentos.id', 'desc');



        // // adicionar cláusula para trabalharmos apenas com lançamentos da movimentação
        // $this->crud->addClause('select', 'lancamentos.*', 'codigoitens.descricao');
        // $this->crud->addClause('join', 'movimentacaocontratocontas', 'movimentacaocontratocontas.id',  '=',  'lancamentos.movimentacao_id');
        // $this->crud->addClause('join', 'codigoitens', 'codigoitens.id',  '=',  'movimentacaocontratocontas.tipo_id');
        // $this->crud->addClause('where', 'lancamentos.movimentacao_id', '=', $movimentacaocontratoconta_id);




        // $this->crud->addClause('where', 'movimentacaocontratocontas.contratoconta_id', '=', $contratoconta_id);
        // $this->crud->addClause('orderby', 'movimentacaocontratocontas.ano_competencia', 'desc');
        // $this->crud->addClause('orderby', 'movimentacaocontratocontas.mes_competencia', 'desc');
        // $this->crud->addClause('orderby', 'movimentacaocontratocontas.id', 'desc');




    }

    public function Colunas()
    {
        $colunas = [

            [
                'name'  => 'nome',
                'label' => 'Funcionário',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('nome', 'ilike', "%$searchTerm%");
                },

            ],

            [
                'name'  => 'nome_movimentacao',
                'label' => 'Movimentação',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('c2.descricao', 'ilike', "%$searchTerm%");
                },
            ],

            [
                'name'  => 'nome_encargo',
                'label' => 'Encargo',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('c1.descricao', 'ilike', "%$searchTerm%");
                },

            ],

            [
                'name'  => 'valor',
                'label' => 'Valor',
                'type'  => 'text',
                'prefix' => 'R$ '
            ],





            // [
            //     'name' => 'getNomePessoaContratoTerceirizado',
            //     'label' => 'Terceirizado', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'getNomePessoaContratoTerceirizado', // the method in your Model
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
            //     'name' => 'getSalarioContratoTerceirizado',
            //     'label' => 'Salário', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'getSalarioContratoTerceirizado', // the method in your Model
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
            //     'label' => 'Encargo', // Table column heading
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
            // [
            //     'name' => 'getPercentualEncargo',
            //     'label' => 'Percentual', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'getPercentualEncargo', // the method in your Model
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
            //     'name'  => 'descricao',
            //     'label' => 'Tipo da movimentação',
            //     'type'  => 'text',
            // ],
            // [
            //     'name' => 'formatValor',
            //     'label' => 'Valor lançamento', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'formatValor', // the method in your Model
            //     'prefix' => "R$ ",
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
