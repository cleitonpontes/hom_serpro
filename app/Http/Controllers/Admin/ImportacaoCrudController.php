<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportacaoRequest as StoreRequest;
use App\Http\Requests\ImportacaoRequest as UpdateRequest;
use App\Models\Codigoitem;
use App\Models\Contrato;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class ImportacaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImportacaoCrudController extends CrudController
{

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        if (!backpack_user()->hasRole('Administrador') or
            !backpack_user()->hasRole('Administrador Órgão') or
            !backpack_user()->hasRole('Administrador Unidade')) {
            abort('403', config('app.erro_permissao'));
        }

        $this->crud->setModel('App\Models\Importacao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'admin/importacao');
        $this->crud->setEntityNameStrings('importacao', 'importações');
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Global
        |--------------------------------------------------------------------------
        */
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();

        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('importacao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('importacao_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('importacao_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Collumns Table
        |--------------------------------------------------------------------------
        */
        $colunas = $this->colunas();
        $this->crud->addColumns($colunas);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Campos Formulário
        |--------------------------------------------------------------------------
        */
        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Importação');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $situacoes = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situação Arquivo');
        })
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $contratos = Contrato::select(DB::raw("CONCAT(contratos.numero,' | ',fornecedores.cpf_cnpj_idgener,' - ',fornecedores.nome) AS nome"), 'contratos.id')
            ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
            ->where('unidade_id', session()->get('user_ug_id'))
            ->where('situacao', true)
            ->orderBy('contratos.numero', 'asc')->pluck('nome', 'id')->toArray();


        if(backpack_user()->hasRole('Administrador Unidade')){
            $roles = Role::where('guard_name','web')
                ->where('name','<>','Administrador')
                ->where('name','<>','Administrador Órgão')
                ->where('name','<>','Administrador Unidade')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        if(backpack_user()->hasRole('Administrador Órgão')){
            $roles = Role::where('guard_name','web')
                ->where('name','<>','Administrador')
                ->where('name','<>','Administrador Órgão')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        if(backpack_user()->hasRole('Administrador')){
            $roles = Role::where('guard_name','web')
                ->where('name','<>','Administrador')
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        $campos = $this->campos($tipos, $unidade, $contratos, $situacoes, $roles);
        $this->crud->addFields($campos);

    }

    public function colunas()
    {
        return [
            [
                'name' => 'nome_arquivo',
                'label' => 'Nome Arquivo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getContrato',
                'label' => 'Número Contrato', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getContrato', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getGrupoUsuarios',
                'label' => 'Grupo Usuário', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getGrupoUsuarios', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'disk' => 'local',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'mensagem',
                'label' => 'Mensagem',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Situacao', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],

        ];
    }

    public function campos($tipos, $unidade, $contratos, $situacoes, $roles)
    {
        return [
            [
                'name' => 'nome_arquivo',
                'label' => 'Nome do Arquivo',
                'type' => 'text',
            ],
            [
                'name' => 'tipo_id',
                'label' => "Tipo",
                'type' => 'select2_from_array',
                'options' => $tipos,
                'allows_null' => true,
            ],
            [
                'name' => 'unidade_id',
                'label' => "Unidade Gestora",
                'type' => 'select2_from_array',
                'options' => $unidade,
                'allows_null' => false,
            ],
            [
                'name' => 'contrato_id',
                'label' => "Contrato",
                'type' => 'select2_from_array',
                'options' => $contratos,
                'allows_null' => true,
            ],
            [
                'name' => 'role_id',
                'label' => "Grupo Usuário",
                'type' => 'select2_from_array',
                'options' => $roles,
                'allows_null' => true,
            ],
            [
                'name' => 'delimitador',
                'label' => 'Delimitador',
                'type' => 'text',
                'limit' => 10
            ],
            [
                'name' => 'arquivos',
                'label' => 'Arquivos',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public'
            ],
            [
                'name' => 'situacao_id',
                'label' => "Situação",
                'type' => 'select2_from_array',
                'options' => $situacoes,
                'allows_null' => true,
            ],
        ];

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
