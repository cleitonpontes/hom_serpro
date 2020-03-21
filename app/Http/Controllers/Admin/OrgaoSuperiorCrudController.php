<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Execfin\EmpenhoCrudController;
use App\Models\OrgaoSuperior;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

// VALIDATION: change the requests to match your own file names if you need form validation

use App\Http\Requests\OrgaoSuperiorRequest as StoreRequest;
use App\Http\Requests\OrgaoSuperiorRequest as UpdateRequest;

/**
 * Class OrgaoSuperiorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrgaoSuperiorCrudController extends CrudController
{
    public function setup()
    {

        if (!backpack_user()->hasRole('Administrador')) {
            abort('403', config('app.erro_permissao'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OrgaoSuperior');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/orgaosuperior');
        $this->crud->setEntityNameStrings('Órgãos Superiores', 'Órgãos Superiores');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->hasRole('Administrador')) ? $this->crud->addButtonFromView('top', 'atualizaorgaosuperior',
            'atualizaorgaosuperior', 'end') : null;

        (backpack_user()->can('orgaosuperior_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('orgaosuperior_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('orgaosuperior_deletar')) ? $this->crud->allowAccess('delete') : null;


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos();
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in OrgaoSuperiorRequest
        // add asterisk for fields that are required in ContratoempenhoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'codigo',
                'label' => 'Código', // Table column heading
                'type' => 'text',
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
                'name' => 'nome',
                'label' => 'Nome', // Table column heading
                'type' => 'text',
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

        ];

        return $colunas;

    }

    public function Campos()
    {

        $campos = [
            [ // select_from_array
                'name' => 'codigo',
                'label' => "Código",
                'type' => 'orgao',
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'nome',
                'label' => "Nome",
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
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

    public function executaAtualizacaoCadastroOrgaoSuperior()
    {
        if (!backpack_user()->hasRole('Administrador')) {
            abort('403', config('app.erro_permissao'));
        }

        $url = config('migracao.api_sta'). '/api/estrutura/orgaossuperiores';

        $funcao = new AdminController();

        $dados = $funcao->buscaDadosUrlMigracao($url);

        foreach ($dados as $dado) {
            $orgao_superior = OrgaoSuperior::where('codigo',$dado['codigo'])
                ->first();

            if(!isset($orgao_superior->codigo)){
                $novo = new OrgaoSuperior();
                $novo->codigo = $dado['codigo'];
                $novo->nome = $dado['nome'];
                $novo->situacao = true;
                $novo->save();
            }else{
                if($orgao_superior->nome != $dado['nome']){
                    $orgao_superior->nome = $dado['nome'];
                    $orgao_superior->save();
                }
            }
        }

        return redirect('admin/orgaosuperior');

    }



}
