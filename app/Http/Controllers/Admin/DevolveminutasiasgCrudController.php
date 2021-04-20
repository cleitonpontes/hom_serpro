<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// inserido
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DevolveminutasiasgRequest as StoreRequest;
use App\Http\Requests\DevolveminutasiasgRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class DevolveminutasiasgCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DevolveminutasiasgCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Devolveminutasiasg');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'admin/devolveminutasiasg');
        $this->crud->setEntityNameStrings('devolveminutasiasg', 'Devolve NE Siasg');

        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');

        (backpack_user()->can('devolveminuta_editar')) ? $this->crud->allowAccess('update') : null;

        $this->crud->addColumns($this->colunas());
        // $campos = $this->Campos($tiposDeEncargo);
        // $this->crud->addFields($campos);



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in DevolveminutasiasgRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }


    private function colunas(): array
    {
        return [
            [
                'name' => 'minutaempenho_id',
                'label' => 'Minuta empenho', // Table column heading
                // 'type' => 'model_function',
                // 'function_name' => 'getDescricaoCodigoItem', // the method in your Model
                // 'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                // 'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
                // },

            ],
            [
                'name' => 'situacao',
                'label' => 'Situação', // Table column heading
                // 'type' => 'model_function',
                // 'function_name' => 'formatPercentual', // the method in your Model
                // 'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                // 'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'created_at',
                'label' => 'Criado em', // Table column heading
                // 'type' => 'model_function',
                // 'function_name' => 'formatPercentual', // the method in your Model
                // 'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                // 'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'updated_at',
                'label' => 'Atualizado em', // Table column heading
                // 'type' => 'model_function',
                // 'function_name' => 'formatPercentual', // the method in your Model
                // 'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                // 'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'alteracao',
                'label' => 'Alteração?', // Table column heading
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [false => 'Não', true => 'Sim']

            ],
            [
                'name' => 'minutaempenho_remessa_id',
                'label' => 'Minuta Empenho Remessa', // Table column heading
                // 'type' => 'model_function',
                // 'function_name' => 'formatPercentual', // the method in your Model
                // 'orderable' => true,
                // 'visibleInTable' => true, // no point, since it's a large text
                // 'visibleInModal' => true, // would make the modal too big
                // 'visibleInExport' => true, // not important enough
                // 'visibleInShow' => true, // sure, why not
            ],
        ];
    }

    public function Campos($tiposDeEncargo)
    {
        $campos = [
            [
                // select_from_array
                'name' => 'tipo_id',
                'label' => "Tipo",
                // 'type' => 'text',
                'type' => 'select2_from_array',
                'options' => $tiposDeEncargo,
            ],

            [
                // Number
                'name' => 'percentual',
                'label' => 'Percentual',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'percentual',
                ], // allow decimals
                // 'prefix' => "R$",
                // 'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],


            // [   // Number
            //     'name' => 'percentual',
            //     'label' => 'Percentual',
            //     'type' => 'money',
            //     // optionals
            //     'suffix' => " %",
            //     'default' => 0,
            //     // 'suffix' => ".00",
            //     // optionals
            //     'attributes' => [
            //         "step" => "any",
            //         "min" => '1',
            //     ], // allow decimals

            // ],
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
