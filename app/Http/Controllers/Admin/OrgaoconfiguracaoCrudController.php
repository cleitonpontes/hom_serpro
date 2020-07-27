<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orgao;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OrgaoconfiguracaoRequest as StoreRequest;
use App\Http\Requests\OrgaoconfiguracaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;

/**
 * Class OrgaoconfiguracaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrgaoconfiguracaoCrudController extends CrudController
{
    public function setup()
    {
        $orgao_id = \Route::current()->parameter('orgao_id');

        $orgao = Orgao::find($orgao_id);
        if (!$orgao or (!(backpack_user()->hasRole('Administrador')) and (backpack_user()->hasRole('Administrador Órgão') and backpack_user()->unidade->sisg))) {
            abort('403', config('app.erro_permissao'));
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Orgaoconfiguracao');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/orgao/' . $orgao_id . '/configuracao');
        $this->crud->setEntityNameStrings('Configuração do Órgão', 'Configuração do Órgão');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarorgao', 'end');


        $this->crud->addClause('where', 'orgao_id', '=', $orgao_id);
        $this->crud->addClause('where', 'orgao_id', '=', $orgao_id);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('orgaosubcategorias_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('orgaosubcategorias_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('orgaosubcategorias_deletar')) ? $this->crud->allowAccess('delete') : null;
        (backpack_user()->can('orgaosubcategorias_inserir')) ? $this->crud->allowAccess('delete') : null;

        (backpack_user()->hasRole('Administrador')) ? $this->crud->addButtonFromView('line', 'executarmigracao', 'executarmigracao', 'beginning') : null;

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos($orgao_id);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in OrgaoconfiguracaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function colunas()
    {
        $colunas = [
            [
                'name' => 'getOrgao',
                'label' => 'Órgão', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getOrgao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $q, $column, $searchTerm) {
//                    $q->where('orgaos.codigo', 'like', "%".strtoupper($searchTerm)."%");
//                    $q->orWhere('orgaos.nome', 'like', "%".strtoupper($searchTerm)."%");
//                },
            ],
            [
                'name' => 'padrao_processo_marcara',
                'label' => 'Padrão Formato Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'api_migracao_conta_url',
                'label' => 'URL API Migração Conta',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'api_migracao_conta_token',
                'label' => 'Token API Migração Conta',
                'type' => 'text',
                'limit' => 20,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;
    }

    public function Campos($orgao)
    {
        $campos = [
            [ // select_from_array
                'name' => 'orgao_id',
                'type' => 'hidden',
                'value' => $orgao,
            ],
            [
                'name' => 'padrao_processo_marcara',
                'label' => 'Padrão Formato Processo',
                'type' => 'text',
                'default' => '99999.999999/9999-99',
                'tab' => 'Básico',
            ],
            [
                'name' => 'api_migracao_conta_url',
                'label' => 'URL API Migração Conta',
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "minusculo(this)"
                ],
                'tab' => 'Migração Conta',
            ],
            [
                'name' => 'api_migracao_conta_token',
                'label' => 'Token API Migração Conta',
                'type' => 'text',
                'tab' => 'Migração Conta',
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
}
