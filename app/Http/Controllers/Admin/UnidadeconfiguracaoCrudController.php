<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UnidadeconfiguracaoRequest as UpdateRequest;
use App\Models\BackpackUser;
use App\Models\Codigoitem;
use App\Models\Fornecedor;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UnidadeconfiguracaoRequest as StoreRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class UnidadeconfiguracaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UnidadeconfiguracaoCrudController extends CrudController
{
    public function setup()
    {
        $unidade_id = \Route::current()->parameter('unidade_id');

        $unidade = Unidade::find($unidade_id);
        if (!$unidade) {
            abort('403', config('app.erro_permissao'));
        }

        $ug_user = [];
        if(backpack_user()->ugprimaria){
            $ug_user[1] = backpack_user()->ugprimaria;
        }

        $ugs = backpack_user()->unidades;

        foreach ($ugs as $u){
            $ug_user[] = $u->id;
        }

        $ug_user = array_unique($ug_user);

        if(array_search($unidade_id,$ug_user) == false){
            abort('403', config('app.erro_permissao'));
        }



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Unidadeconfiguracao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/unidade/' . $unidade_id . '/configuracao');
        $this->crud->setEntityNameStrings('Configuração da Unidade', 'Configuração da Unidade');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarunidade', 'end');
        $this->crud->addClause('where', 'id', '=', $unidade_id);
//        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'unidadeconfiguracao.unidade_id');
//        $this->crud->addClause('join', 'users', 'users.id', '=', 'unidadeconfiguracao.user1_id');
//        $this->crud->addClause('select', 'unidadeconfiguracao.*');

        $this->crud->enableExportButtons();
        // add asterisk for fields that are required in UnidadeconfiguracaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('unidadeconfiguracao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('unidadeconfiguracao_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('unidadeconfiguracao_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $ug = $unidade->select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->where('id',$unidade_id)
            ->pluck('nome','id')
            ->toArray();

        $users = BackpackUser::select(DB::raw("CONCAT(cpf,' - ',name) AS nome"), 'id')
        ->whereHas('unidades', function ($q) use ($unidade) {
            $q->where('unidade_id',$unidade->id);
        })
        ->orWhere('ugprimaria',$unidade->id)
            ->orderBy('nome')
        ->pluck('nome','id')
        ->toArray();

        $campos = $this->Campos($ug, $users);
        $this->crud->addFields($campos);



    }

    public function colunas()
    {
        $colunas = [
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('unidades.codigo', 'like', "%".strtoupper($searchTerm)."%");
//                    $query->orWhere('unidades.nomeresumido', 'like', "%".strtoupper($searchTerm)."%");
//                },
            ],
            [
                'name' => 'getUser1',
                'label' => 'Chefe Setor Contratos', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUser1', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('users.cpf', 'like', "%".strtoupper($searchTerm)."%");
//                    $query->orWhere('users.nome', 'like', "%".strtoupper($searchTerm)."%");
//                },
            ],
            [
                'name' => 'getUser2',
                'label' => 'Substituto Setor Contratos', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUser2', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('users.cpf', 'like', "%".strtoupper($searchTerm)."%");
//                    $query->orWhere('users.nome', 'like', "%".strtoupper($searchTerm)."%");
//                },
            ],
            [
                'name' => 'getUser3',
                'label' => 'Ordenador Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUser3', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('users.cpf', 'like', "%".strtoupper($searchTerm)."%");
//                    $query->orWhere('users.nome', 'like', "%".strtoupper($searchTerm)."%");
//                },
            ],
            [
                'name' => 'getUser4',
                'label' => 'Substituto Ordenador Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUser4', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('users.cpf', 'like', "%".strtoupper($searchTerm)."%");
//                    $query->orWhere('users.nome', 'like', "%".strtoupper($searchTerm)."%");
//                },
            ],
            [
                'name' => 'telefone1',
                'label' => 'Telefone 1',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'telefone2',
                'label' => 'Telefone 2',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'email_diario',
                'label' => 'Rotina de E-mail Diário?',
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
                'name' => 'email_diario_periodicidade',
                'label' => 'Periodicidade E-mail Diário',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'email_diario_texto',
                'label' => 'Texto E-mail Diário',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'email_mensal',
                'label' => 'Rotina de Extrato Mensal?',
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
                'name' => 'email_mensal_dia',
                'label' => 'Dia Envio Extrato Mensal',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'email_mensal_texto',
                'label' => 'Texto Extrato Mensal',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;
    }

    public function Campos($unidade, $users)
    {
        $campos = [
            [ // select_from_array
                'name' => 'unidade_id',
                'label' => "Unidade",
                'type' => 'select_from_array',
                'options' => $unidade,
                'allows_null' => false,
                'tab' => 'Dados Gerais',
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                // select_from_array
                'name' => 'user1_id',
                'label' => "Chefe Contratos",
                'type' => 'select2_from_array',
                'options' => $users,
//                'attributes' => [
//                    'id' => 'tipo_contrato',
//                ],
                'allows_null' => true,
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                // select_from_array
                'name' => 'user2_id',
                'label' => "Substituto Chefe Contratos",
                'type' => 'select2_from_array',
                'options' => $users,
//                'attributes' => [
//                    'id' => 'tipo_contrato',
//                ],
                'allows_null' => true,
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                // select_from_array
                'name' => 'user3_id',
                'label' => "Ordenador Despesa",
                'type' => 'select2_from_array',
                'options' => $users,
//                'attributes' => [
//                    'id' => 'tipo_contrato',
//                ],
                'allows_null' => true,
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                // select_from_array
                'name' => 'user4_id',
                'label' => "Substituto Ordenador Despesa",
                'type' => 'select2_from_array',
                'options' => $users,
//                'attributes' => [
//                    'id' => 'tipo_contrato',
//                ],
                'allows_null' => true,
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'telefone1',
                'label' => 'Telefone 1',
                'type' => 'telefone',
                'tab' => 'Dados Gerais',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [
                'name' => 'telefone2',
                'label' => 'Telefone 2',
                'type' => 'telefone',
                'tab' => 'Dados Gerais',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [ // select_from_array
                'name' => 'email_diario',
                'label' => "Rotina Diária E-mails?",
                'type' => 'radio',
                'options' => [0 => 'Não', 1 => 'Sim'],
                'default' => 1,
                'inline'  => true,
                'tab' => 'Rotina Diária',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ],
            [ // select_from_array
                'name' => 'email_diario_periodicidade',
                'label' => "Periodicidade E-mails",
                'default' => '30;60;90;120;150;180',
                'type' => 'text',
                'tab' => 'Rotina Diária',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [ // select_from_array
                'name' => 'email_diario_texto',
                'label' => "Texto E-mail",
                'type' => 'ckeditor',
                'tab' => 'Rotina Diária',
            ],
            [ // select_from_array
                'name' => 'email_mensal',
                'label' => "Extrato Mensal?",
                'type' => 'radio',
                'options' => [0 => 'Não', 1 => 'Sim'],
                'default' => 1,
                'inline'  => true,
                'tab' => 'Extrato Mensal',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ],
            [ // select_from_array
                'name' => 'email_mensal_dia',
                'label' => "Envia Extrato que dia do Mês?",
                'default' => '1',
                'type' => 'number',
                'tab' => 'Extrato Mensal',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],
            [ // select_from_array
                'name' => 'email_mensal_texto',
                'label' => "Texto E-mail",
                'type' => 'ckeditor',
                'tab' => 'Extrato Mensal',
            ],
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

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'unidade_id',
            'user1_id',
            'user2_id',
            'user3_id',
            'user4_id',
        ]);


        return $content;
    }
}
