<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratounidadedescentralizadaRequest as StoreRequest;
use App\Http\Requests\ContratounidadedescentralizadaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Eloquent\Builder;

/**
 * Class ContratounidadedescentralizadaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratounidadedescentralizadaCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratounidadedescentralizada');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/contratounidadedescentralizada');
        $this->crud->setEntityNameStrings('contratounidadedescentralizada', 'Unidades Descentralizadas');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);

        // dd($this->crud)->query;


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        // $campos = $this->Campos($contrato);
        // $this->crud->addFields($campos);



        // add asterisk for fields that are required in ContratounidadedescentralizadaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getContrato',
                'label' => 'Número do Instrumento', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                // },
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
