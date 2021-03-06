<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\FuncoescontratocontaRequest as StoreRequest;
use App\Http\Requests\FuncoescontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Contratoconta;
use App\Models\Contratoterceirizado;
use App\Models\Movimentacaocontratoconta;


/**
 * Class FuncoescontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FuncoescontratocontaCrudController extends CrudController
{
    public function setup()
    {
        $contratoconta_id = \Route::current()->parameter('contratoconta_id');
        $objContratoConta = Contratoconta::where('id', '=', $contratoconta_id)->first();
        if(!$objContratoConta){
            abort('403', config('app.erro_permissao'));
        }
        $idContrato = $objContratoConta->contrato_id;
        $idContratoConta = $objContratoConta->id;

        \Route::current()->setParameter('contrato_id', $idContrato);
        \Route::current()->setParameter('contratoconta_id', $idContratoConta);

        $this->crud->addButtonFromView('line', 'morefuncoescontratoconta', 'morefuncoescontratoconta', 'end');

        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('create');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Funcoescontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/funcoescontratoconta');
        $this->crud->setEntityNameStrings('funcoescontratoconta', 'Fun????es para Repactua????o');

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


        // add asterisk for fields that are required in FuncoescontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // cl??usulas para possibilitar buscas
        $this->crud->addClause('select', 'ci.*')->distinct();
        $this->crud->addClause('join', 'codigoitens as ci', 'ci.id',  '=',  'contratoterceirizados.funcao_id');
        $this->crud->addClause('where', 'contratoterceirizados.contrato_id', '=', $idContrato);
        $this->crud->addClause('where', 'contratoterceirizados.situacao', '=', 't');


    }
    public function Colunas()
    {
        $colunas = [

            [
                'name'  => 'descricao',
                'label' => 'Fun????o',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('descricao', 'ilike', "%$searchTerm%");
                },

            ],
            [
                'name' => 'getSalariosDaFuncaoContrato',
                'label' => 'Remunera????o conforme contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSalariosDaFuncaoContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                // },
            ],


            // [
            //     'name'  => 'nome_movimentacao',
            //     'label' => 'Movimenta????o',
            //     'type'  => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //         $query->orWhere('c2.descricao', 'ilike', "%$searchTerm%");
            //     },
            // ],

            // [
            //     'name'  => 'mes_competencia',
            //     'label' => 'M??s',
            //     'type'  => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //     //     $query->orWhere('c2.descricao', 'ilike', "%$searchTerm%");
            //     // },
            // ],

            // [
            //     'name'  => 'ano_competencia',
            //     'label' => 'Ano',
            //     'type'  => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //     //     $query->orWhere('c2.descricao', 'ilike', "%$searchTerm%");
            //     // },
            // ],

            // [
            //     'name'  => 'nome_encargo',
            //     'label' => 'Verba',
            //     'type'  => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //         $query->orWhere('c1.descricao', 'ilike', "%$searchTerm%");
            //     },

            // ],

            // [
            //     'name'  => 'valor',
            //     'label' => 'Valor',
            //     'type'  => 'text',
            //     'prefix' => 'R$ '
            //     // 'orderable' => true,
            //     // 'visibleInTable' => true, // no point, since it's a large text
            //     // 'visibleInModal' => true, // would make the modal too big
            //     // 'visibleInExport' => true, // not important enough
            //     // 'visibleInShow' => true, // sure, why not
            //     // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //     //     $query->orWhere('lancamentos.valor', 'ilike', "%$searchTerm%");
            //     // },

            // ],
            // [
            //     'name'  => 'data_lancamento',
            //     'label' => 'Data / Hora',
            //     'type'  => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //         $query->orWhere('lancamentos.created_at', 'ilike', "%$searchTerm%");
            //     },
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
