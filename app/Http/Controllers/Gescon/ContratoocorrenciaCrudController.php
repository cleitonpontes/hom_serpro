<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoocorrenciaRequest as StoreRequest;
use App\Http\Requests\ContratoocorrenciaRequest as UpdateRequest;

/**
 * Class ContratoocorrenciaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoocorrenciaCrudController extends CrudController
{
    public function setup()
    {

        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato){
            abort('403', 'Acesso negado - você não possui a permissão necessária para acessar esta página.');
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratoocorrencia');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/ocorrencias');
        $this->crud->setEntityNameStrings('Ocorrências', 'ocorrências');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        $conresp = $contrato->whereHas('responsaveis', function ($query) {
            $query->whereHas('user', function ($query) {
                $query->where('id', '=', backpack_user()->id);
            });
        })->where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();

        if ($conresp) {
            $this->crud->AllowAccess('create');
            $this->crud->AllowAccess('update');
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->orderBy('numero','asc');

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumns([
            [
                'name'  => 'numero',
                'label' => 'Número',
                'type'  => 'text',
            ],
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getUser',
                'label' => 'Usuário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUser', // the method in your Model
                'orderable' => true,
                'limit' => 255,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name'  => 'data',
                'label' => 'Data',
                'type'  => 'date',
            ],
            [
                'name'  => 'ocorrencia',
                'label' => 'Ocorrência',
                'type'  => 'textarea',
                'limit' => 9999,
            ],
            [
                'name'  => 'ocorrencia',
                'label' => 'Ocorrência',
                'type'  => 'textarea',
            ],
            [
                'name' => 'notificapreposto',
                'label' => 'Notifica Preposto',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name'  => 'emailpreposto',
                'label' => 'E-mail Preposto',
                'type'  => 'text',
            ],
            [
                'name'  => 'numeroocorrencia',
                'label' => 'Ocorrência Alterada',
                'type'  => 'text',
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Nova Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
            ],
        ]);


        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $situacao = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situação Ocorrência');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $novasit = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situação Ocorrência');
        })
            ->where('descricao', '<>', 'Pendente')
            ->where('descricao', '<>', 'Conclusiva')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();



        // add asterisk for fields that are required in ContratoocorrenciaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
