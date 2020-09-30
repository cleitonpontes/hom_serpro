<?php

namespace App\Http\Controllers\Gescon;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\FuncionarioscontratocontaRequest as StoreRequest;
use App\Http\Requests\FuncionarioscontratocontaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FuncionarioscontratocontaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FuncionarioscontratocontaCrudController extends CrudController
{
    public function setup()
    {

        $contratoconta_id = \Route::current()->parameter('contratoconta_id');



        // echo ' ==> '.\Route::current()->parameter('contratoterceirizado.id'); exit;
        // echo ' ==> '.\Route::current()->parameter('id');

        //vamos setar o contrato_id como parâmetro e utilizá-lo no botão
        // \Route::current()->setParameter('contrato_id', $contrato_id);



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Funcionarioscontratoconta');
        // $this->crud->setRoute(config('backpack.base.route_prefix') . '/funcionarioscontratoconta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/contratoconta/' . $contratoconta_id . '/funcionarioscontratoconta');
        $this->crud->setEntityNameStrings('Funcionário', 'Selecione o funcionário');

        // cláusulas para trazer apenas os contratos terceirizados do contratoconta_id
        $this->crud->addClause('select', 'contratoterceirizados.*');
        $this->crud->addClause('join', 'contratos', 'contratos.id', 'contrato_id');
        $this->crud->addClause('join', 'contratocontas', 'contratocontas.contrato_id', 'contratos.id');
        $this->crud->addClause('where', 'contratocontas.id', '=', $contratoconta_id);

        $this->crud->addButtonFromView('line', 'morefuncionarioscontratoconta', 'morefuncionarioscontratoconta', 'end');

        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('create');


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        // add asterisk for fields that are required in FuncionarioscontratocontaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'nome',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'cpf',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            // [
            //     'name' => 'agencia',
            //     'type' => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            // ],
            // [
            //     'name' => 'conta_corrente',
            //     'type' => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            // ],
            // [
            //     'name' => 'fat_empresa',
            //     'type' => 'text',
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            // ],
            // [
            //     'name' => 'getSaldoContratoContaParaColunas',
            //     'label' => 'Saldo', // Table column heading
            //     'type' => 'model_function',
            //     'function_name' => 'getSaldoContratoContaParaColunas', // the method in your Model
            //     'orderable' => true,
            //     'visibleInTable' => true, // no point, since it's a large text
            //     'visibleInModal' => true, // would make the modal too big
            //     'visibleInExport' => true, // not important enough
            //     'visibleInShow' => true, // sure, why not
            //     'prefix' => "R$ ",
            //     // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
            //     //     $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
            //     // },
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
