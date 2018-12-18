<?php

namespace App\Http\Controllers\Admin;

use App\Models\Codigo;
use App\Models\Codigoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CodigoitemRequest as StoreRequest;
use App\Http\Requests\CodigoitemRequest as UpdateRequest;

/**
 * Class CodigoitemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CodigoitemCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $codigo_id = \Route::current()->parameter('codigo_id');

        $codigo = Codigo::find($codigo_id);

        if($codigo->visivel == false){
            abort('403', 'Sem permissão!');
        }

        $this->crud->setModel('App\Models\Codigoitem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/codigo/'.$codigo_id.'/codigoitem');
        $this->crud->setEntityNameStrings('Código Itens', 'Código Itens');

        $this->crud->addClause('where', 'codigo_id', '=', $codigo_id);

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('codigoitem_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('codigoitem_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('codigoitem_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        $this->crud->addColumn([    // SELECT
            'label'     => 'Código',
            'type'      => 'select',
            'name'      => 'codigo_id',
            'entity'    => 'codigo',
            'attribute' => 'descricao',
            'model'     => "App\Models\Codigo",
            'pivot' => true
        ]);

        $this->crud->addColumn([
            'name'  => 'descres',
            'label' => 'Descrição Resumida',
            'type'  => 'text',
        ]);

        $this->crud->addColumn([
            'name'  => 'descricao',
            'label' => 'Descrição',
            'type'  => 'text',
        ]);

        $this->crud->addField([ // select_from_array
            'name'            => 'codigo_id',
            'label'           => 'Código',
            'type'            => 'select_from_array',
            'options'         => [$codigo->id => $codigo->descricao],
            'allows_null'     => false,
            'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        $this->crud->addField([
            'name'  => 'descres',
            'label' => 'Descrição Resumida',
            'type'  => 'text',
        ]);

        $this->crud->addField([
            'name'  => 'descricao',
            'label' => 'Descrição',
            'type'  => 'text',
        ]);


        // add asterisk for fields that are required in CodigoitemRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcodigo', 'end');
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

        $this->crud->removeColumn('visivel');

        return $content;
    }
}
