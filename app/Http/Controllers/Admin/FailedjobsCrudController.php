<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\FailedjobsRequest as StoreRequest;
use App\Http\Requests\FailedjobsRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class FailedjobsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FailedjobsCrudController extends CrudController
{
    public function setup()
    {

        if(!backpack_user()->hasRole('Administrador')){
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Failedjobs');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/failedjobs');
        $this->crud->setEntityNameStrings('Failed Jobs', 'Failed Jobs');

        $this->crud->addClause('orderby', 'failed_at', 'DESC');



        backpack_user()->hasRole('Administrador') ? $this->crud->allowAccess('show') : $this->crud->denyAccess('show');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');


        $this->crud->enableExportButtons();
        $this->crud->addButtonFromView('line', 'retryfailedjob', 'retryfailedjob');


        // colunas da listagem
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);




        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in FailedjobsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }


    public function Colunas()
    {

        $colunas = [

            [
                'name'  => 'connection',
                'label' => 'Connection',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('feriados.data', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name'  => 'queue',
                'label' => 'Queue',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('feriados.data', 'ilike', "%$searchTerm%");
                // },
            ],

            [
                'name'  => 'payload',
                'label' => 'Payload',
                'type'  => 'text',
                'limit' => 9999,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('feriados.data', 'ilike', "%$searchTerm%");
                // },
            ],

            [
                'name'  => 'exception',
                'label' => 'Exception',
                'type'  => 'text',
                'limit' => 9999,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('exception', 'ilike', "%$searchTerm%");
                // },
            ],
            [
                'name'  => 'failed_at',
                'label' => 'Failed at',
                'type'  => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('feriados.data', 'ilike', "%$searchTerm%");
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
