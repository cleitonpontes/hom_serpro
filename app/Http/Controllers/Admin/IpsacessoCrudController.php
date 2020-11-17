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
        $this->crud->setModel('App\Models\Ipsacesso');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/ipsacesso');
        $this->crud->setEntityNameStrings('IP', "Controle de acesso por IP's");

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        
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
        $orgaos = Orgao::all()->pluck('nome', 'id')->toArray();
               
        $campos = [
            [
                'name' => 'orgao_id',
                'label' => "Órgão",
                'type' => 'select2_from_array',
                'options' => $orgaos,
                'placeholder' => "Todos",
                'allows_null' => true
            ],
            [
                'label' => "Unidade", 
                'type' => 'select2_from_ajax',
                'name' => 'unidade_id', 
                'model' => 'App\Models\Unidade',
                'attribute' => 'nomeresumido', 
                'entity' => 'unidade',
                'data_source' => url('api/unidade'),
                'placeholder' => 'Selecione...', 
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
                'label'       => 'Orgão',
                'type'        => 'model_function',
                'name'        => 'orgao_id',
                'entity'      => 'orgao',
                'attribute'   => 'nome', // combined name & date column
                'model'       => 'App\Models\Orgao',
                'function_name' => 'getOrgao',
                 'searchLogic' => function ($query, $column, $searchTerm) {
                     $query->orWhereHas('orgao', function ($q) use ($column, $searchTerm) {
                         $q->where('orgaos.nome', 'like', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");                    
                     });
                 }
            ],
            [
                'label'       => 'Unidade',
                'type'        => 'model_function',
                'name'        => 'unidade_id',
                'entity'      => 'unidade',
                'attribute'   => 'nome', // combined name & date column
                'model'       => 'App\Models\Unidade',
                'function_name' => 'getUnidade',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('unidade', function ($q) use ($column, $searchTerm) {
                        $q->where('unidades.nomeresumido', 'like', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");                    
                    });
                }
            ],
            [
                'name' => 'ips',
                'label' => "Ip's",
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'visibleInTable' => false,
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
            \Alert::error('Sem permissão para cadastrar IPs')->flash();
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
        $usuario = BackpackUser::where('id', '=', \Auth::user()->id)->first();

        if (!$usuario->hasRole('Administrador')) {
            \Alert::error('Sem permissão para alterar IPs')->flash();
            return redirect()->back();
        }

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
