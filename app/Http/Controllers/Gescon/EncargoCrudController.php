<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Encargo;
use Backpack\CRUD\app\Http\Controllers\CrudController;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EncargoRequest as StoreRequest;
use App\Http\Requests\EncargoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;


/**
 * Class EncargoCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class EncargoCrudController extends CrudController
{
    public function setup()
    {

        // buscar os tipos de encargo em codigoitens para seleção
        $tiposDeEncargo = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Encargos');
        })->pluck('descricao', 'id')->toArray();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Encargo');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/encargo');
        $this->crud->setEntityNameStrings('encargo', 'encargos');

        $this->crud->addColumns($this->colunas());

        $campos = $this->Campos($tiposDeEncargo);
        $this->crud->addFields($campos);

        (backpack_user()->can('encargo_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('encargo_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('encargo_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // add asterisk for fields that are required in EncargoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function verificarSeEncargoExiste($request){
        $tipoId = $request->request->get('tipo_id');
        if( Encargo::where('tipo_id', $tipoId)->count() > 0 ){return true;}
        return false;
    }
    public function store(StoreRequest $request)
    {
        // verificar se o encargo ainda não existe.
        if(self::verificarSeEncargoExiste($request)){
            // return Redirect::back()->withErrors(['msg', 'The Message']);
            \Alert::error('Você tentou cadastrar um encargo que já existe!')->flash();
            return redirect()->back();
        }
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


    private function colunas(): array
    {
        return [
            [
                'name' => 'tipo_id',
                'label' => 'Tipo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'percentual',
                'label' => 'Percentual (%)',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
        ];
    }


    public function Campos($tiposDeEncargo)
    {
        $campos = [
            [
                // select from array
                'name' => 'tipo_id',
                'label' => "Tipo",
                'type' => 'select2_from_array',
                'options' => $tiposDeEncargo,
                'allows_null' => false,
            ],
            [   // Number
                'name' => 'percentual',
                'label' => 'Percentual',
                'type' => 'number',
                // optionals
                'suffix' => " %",
                'default' => 0,
                // 'suffix' => ".00",
            ],
        ];

        return $campos;
    }
}
