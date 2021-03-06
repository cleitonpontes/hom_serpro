<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Repositories\Unidade as RepoUnidade;
use App\Models\Orgao;
use App\Models\Unidade;
use App\Models\BackpackUser;
use App\Repositories\OrgaoSuperior as RepoOrgaoSuperior;
use App\Repositories\Comunica as Repo;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\IpsacessoRequest as StoreRequest;
use App\Http\Requests\IpsacessoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;


/**
 * Class IpsacessoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class IpsacessoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        if (!backpack_user()->hasRole('Administrador')) {
            abort('403', config('app.erro_permissao'));
        }

        $this->crud->setModel('App\Models\Ipsacesso');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/ipsacesso');
        $this->crud->setEntityNameStrings('IP', "Controle de acesso por IP's");

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

        (backpack_user()->can('ipapi_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('ipapi_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('ipapi_deletar')) ? $this->crud->allowAccess('delete') : null;


        $this->crud->addFields($this->Campos());
        $this->crud->addColumns($this->colunas());

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in IpsacessoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function campos()
    {
        $orgaos = Orgao::select(DB::raw("CONCAT(codigo,' - ',nome) AS nome"), 'id')->pluck('nome', 'id')->toArray();


        $campos = [
            [
                'name' => 'orgao_id',
                'label' => "??rg??o",
                'type' => 'select2_from_array',
                'options' => $orgaos,
                'placeholder' => "Todos",
                'allows_null' => true
            ],
            [
                'label' => "Unidade",
                'type' => "select2_from_ajax",
                'name' => 'unidade_id',
                'entity' => 'unidade',
                'attribute' => "codigo",
                'attribute2' => "nomeresumido",
                'process_results_template' => 'gescon.process_results_unidade',
                'model' => "App\Models\Unidade",
                'data_source' => url("api/unidadecomorgao"),
                'placeholder' => "Selecione a Unidade",
                'minimum_input_length' => 0,
                'dependencies' => ['orgao_id'],
            ],
            [
                'name' => 'ips',
                'label' => "Ip's",
                'type' => 'table',
                'entity_singular' => 'Ip', // used on the "Add X" button
                'columns' => [
                    'name' => '',
                ],
                'min' => 1, // minimum rows allowed in the table
            ]
        ];

        return $campos;
    }

    private function unidadeId()
    {
        $unidade = '';
        if ($this->crud->getActionMethod() === 'edit'
            && $this->crud->getEntry($this->crud->getCurrentEntryId())->unidade !== null
        ) {
            $unidade = $this->crud->getEntry($this->crud->getCurrentEntryId())->unidade->id;
        }
        return $unidade;
    }

    public function colunas()
    {
        $colunas = [

            [
                'label' => 'Org??o',
                'type' => 'model_function',
                'name' => 'orgao_id',
                'entity' => 'orgao',
                'attribute' => 'nome', // combined name & date column
                'model' => 'App\Models\Orgao',
                'function_name' => 'getOrgao',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('orgao', function ($q) use ($column, $searchTerm) {
                        $q->where('orgaos.nome', 'like', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                    });
                }
            ],
            [
                'label' => 'Unidade',
                'type' => 'model_function',
                'name' => 'unidade_id',
                'entity' => 'unidade',
                'attribute' => 'nome', // combined name & date column
                'model' => 'App\Models\Unidade',
                'function_name' => 'getUnidade',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('unidade', function ($q) use ($column, $searchTerm) {
                        $q->where('unidades.nomeresumido', 'like', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                    });
                }
            ],
            [
                'name' => 'ips',
                'label' => 'Ip??s',
                'type' => 'table',
                'columns' => [
                    'name' => 'Ip??s',
                ],
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
            ],
        ];
        return $colunas;
    }

    public function store(StoreRequest $request)
    {
        $usuario = BackpackUser::where('id', '=', \Auth::user()->id)->first();

        if (!$usuario->hasRole('Administrador')) {
            \Alert::error('Sem permiss??o para cadastrar IPs')->flash();
            return redirect()->back();
        }
        
        // retirar campos vazios do formulario de ips cadastrados
        $request->request->set('ips', str_replace(",{}",'', $request->input('ips')));

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        
        $usuario = BackpackUser::where('id', '=', \Auth::user()->id)->first();
        
        if (!$usuario->hasRole('Administrador')) {
            \Alert::error('Sem permiss??o para alterar IPs')->flash();
            return redirect()->back();
        
        }
        // retirar campos vazios do formulario de ips cadastrados
        $request->request->set('ips', str_replace(",{}",'', $request->input('ips')));
        
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
