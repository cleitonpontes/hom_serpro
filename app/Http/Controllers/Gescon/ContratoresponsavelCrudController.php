<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Instalacao;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoresponsavelRequest as StoreRequest;
use App\Http\Requests\ContratoresponsavelRequest as UpdateRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class ContratoresponsavelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContratoresponsavelCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Contratoresponsavel');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/responsaveis');
        $this->crud->setEntityNameStrings('Responsáveis', 'responsáveis');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('responsavel_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('responsavel_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('responsavel_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();



        $this->crud->addColumns([
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
                'name' => 'getFuncao',
                'label' => 'Função', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFuncao', // the method in your Model
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
                'name' => 'getInstalacao',
                'label' => 'Instalação / Unidade', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getInstalacao', // the method in your Model
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
                'name' => 'portaria',
                'label' => 'Portaria',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
        ]);


        $con = $contrato->where('id', '=', $contrato_id)
            ->pluck('numero', 'id')
            ->toArray();

        $users = BackpackUser::select(DB::raw("CONCAT(cpf,' - ',name) AS nome"), 'id')
            ->where('ugprimaria','=',session()->get('user_ug_id'))
            ->orWhereHas('unidades', function ($query) {
                $query->where('unidade_id', '=', session()->get('user_ug_id'));
            })
            ->orderBy('nome', 'asc')
            ->pluck('nome', 'id')
            ->toArray();

        $funcoes = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Função Contrato');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $instalacoes = Instalacao::orderBy('nome', 'asc')
            ->pluck('nome', 'id')
            ->toArray();

        $this->crud->addFields([
            [ // select_from_array
                'name' => 'contrato_id',
                'label' => "Número Contrato",
                'type' => 'select_from_array',
                'options' => $con,
                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'user_id',
                'label' => "Usuário",
                'type' => 'select2_from_array',
                'options' => $users,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'funcao_id',
                'label' => "Função",
                'type' => 'select2_from_array',
                'options' => $funcoes,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'instalacao_id',
                'label' => "Instalação / Unidade",
                'type' => 'select2_from_array',
                'options' => $instalacoes,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'portaria',
                'label' => 'Portaria',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
        ]);

        // add asterisk for fields that are required in ContratoresponsavelRequest
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
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('user_id');
        $this->crud->removeColumn('funcao_id');
        $this->crud->removeColumn('instalacao_id');

        return $content;
    }
}
