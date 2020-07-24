<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Execfin\EmpenhoCrudController;
use App\Models\Orgao;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Database\Eloquent\Builder;
use App\Models\OrgaoSuperior;
use Illuminate\Support\Facades\DB;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OrgaoRequest as StoreRequest;
use App\Http\Requests\OrgaoRequest as UpdateRequest;

/**
 * Class OrgaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrgaoCrudController extends CrudController
{
    public function setup()
    {

        if (backpack_user()->hasRole('Administrador') or backpack_user()->hasRole('Administrador Órgão')) {
            /*
            |--------------------------------------------------------------------------
            | CrudPanel Basic Information
            |--------------------------------------------------------------------------
            */
            $this->crud->setModel('App\Models\Orgao');
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/orgao');
            $this->crud->setEntityNameStrings('Órgão', 'Órgãos');
            $this->crud->addButtonFromView('line', 'moreorgao', 'moreorgao', 'end');

//        $this->crud->addClause('select', 'orgaos.*');
//        $this->crud->addClause('join', 'orgaossuperiores', 'orgaossuperiores.id', '=', 'orgaos.orgaosuperior_id');

            $this->crud->enableExportButtons();
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');
            $this->crud->allowAccess('show');

            (backpack_user()->hasRole('Administrador')) ? $this->crud->addButtonFromView('top', 'atualizaorgao',
                'atualizaorgao', 'end') : null;

            (backpack_user()->can('orgao_inserir')) ? $this->crud->allowAccess('create') : null;
            (backpack_user()->can('orgao_editar')) ? $this->crud->allowAccess('update') : null;
            (backpack_user()->can('orgao_deletar')) ? $this->crud->allowAccess('delete') : null;

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Configuration
            |--------------------------------------------------------------------------
            */

            // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
            $colunas = $this->Colunas();
            $this->crud->addColumns($colunas);

            $orgaossuperiores = OrgaoSuperior::where('situacao', '=', true)->pluck('nome', 'id')->toArray();

            $campos = $this->Campos($orgaossuperiores);
            $this->crud->addFields($campos);

            // add asterisk for fields that are required in OrgaoRequest
            $this->crud->setRequiredFields(StoreRequest::class, 'create');
            $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        } else {
            abort('403', config('app.erro_permissao'));
        }
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'codigosiasg',
                'label' => 'Código SIASG', // Table column heading
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
                'name' => 'codigo',
                'label' => 'Código SIAFI', // Table column heading
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
                'name' => 'getOrgaoSuperior',
                'label' => 'Órgão Superior', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getOrgaoSuperior', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('orgaossuperiores.codigo', 'like', "%$searchTerm%");
//                    $query->orWhere('orgaossuperiores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
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

    public function Campos($orgaossuperiores)
    {

        $campos = [
            [ // select_from_array
                'name' => 'orgaosuperior_id',
                'label' => "Órgão Superior",
                'type' => 'select2_from_array',
                'options' => $orgaossuperiores,
                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'codigo',
                'label' => "Código SIAFI",
                'type' => 'orgao',
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'codigosiasg',
                'label' => "Código SIASG",
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

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('orgaosuperior_id');

        return $content;
    }

    public function executaAtualizacaoCadastroOrgao()
    {
        if (!backpack_user()->hasRole('Administrador')) {
            abort('403', config('app.erro_permissao'));
        }

        $url = config('migracao.api_sta'). '/api/estrutura/orgaos';

        $funcao = new AdminController();

        $dados = $funcao->buscaDadosUrlMigracao($url);

        foreach ($dados as $dado) {

            $orgao = Orgao::where('codigo',$dado['codigo'])
                ->first();

            if(!isset($orgao->codigo)){

                $orgao_superior = OrgaoSuperior::where('codigo',$dado['orgao_superior'])
                    ->first();

                if(isset($orgao_superior->id)){
                        $novo = new Orgao();
                        $novo->orgaosuperior_id = $orgao_superior->id;
                        $novo->codigo = $dado['codigo'];
                        $novo->codigosiasg = $dado['codigo'];
                        $novo->nome = $dado['nome'];
                        $novo->situacao = true;
                        $novo->save();
                }

            }else{
                if($orgao->nome != $dado['nome'] or $orgao->orgaosuperior->codigo != $dado['orgao_superior']){
                    $orgao_superior = OrgaoSuperior::where('codigo',$dado['orgao_superior'])
                        ->first();
                    if(isset($orgao_superior->id)) {
                        $orgao->orgaosuperior_id = $orgao_superior->id;
                        $orgao->nome = $dado['nome'];
                        $orgao->save();
                    }
                }
            }
        }

        return redirect('admin/orgao');
    }

}
