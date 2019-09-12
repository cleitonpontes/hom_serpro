<?php

namespace App\Http\Controllers\Admin;

use App\Models\Codigoitem;
use App\Models\Orgao;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OrgaoSubcategoriaRequest as StoreRequest;
use App\Http\Requests\OrgaoSubcategoriaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class OrgaoSubcategoriaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrgaoSubcategoriaCrudController extends CrudController
{
    public function setup()
    {
        $orgao_id = \Route::current()->parameter('orgao_id');

        $orgao = Orgao::find($orgao_id);
        if (!$orgao) {
            abort('403', config('app.erro_permissao'));
        }

        $orgaos_user = [];
        if (backpack_user()->ugprimaria) {
            $orgaos_user[1] = backpack_user()->ugPrimariaRelation->orgao_id;
        }

        $ugs = backpack_user()->unidades;

        foreach ($ugs as $u) {
            $orgaos_user[] = $u->orgao_id;
        }


        $orgaos_user = array_unique($orgaos_user);


        if (array_search($orgao_id, $orgaos_user) == false) {
            abort('403', config('app.erro_permissao'));
        }


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OrgaoSubcategoria');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/orgao/'.$orgao_id.'/subcategorias');
        $this->crud->setEntityNameStrings('Subcategoria do Órgão', 'Subcategorias do Órgão');
        $this->crud->addButtonFromView('top', 'voltar', 'voltarorgao', 'end');

//        $this->crud->addClause('select', 'orgaosubcategorias.*');
//        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id', '=', 'orgaosubcategorias.categoria_id');
//        $this->crud->addClause('join', 'orgaos', 'orgaos.id', '=', 'orgaosubcategorias.orgao_id');
        $this->crud->addClause('where', 'orgao_id', '=', $orgao_id);

        $this->crud->enableExportButtons();
        $this->crud->enableResponsiveTable();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('orgaosubcategorias_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('orgaosubcategorias_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('orgaosubcategorias_deletar')) ? $this->crud->allowAccess('delete') : null;


        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $org = $orgao->select(DB::raw("CONCAT(codigo,' - ',nome) AS nome"), 'id')
            ->where('id',$orgao_id)
            ->pluck('nome','id')
            ->toArray();

        $categorias = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Categoria Contrato');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();


        $campos = $this->Campos($org, $categorias);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in OrgaoSubcategoriaRequest
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
                'name' => 'getCategoria',
                'label' => 'Categoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCategoria', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('codigoitens.descricao', 'like', "%" . $searchTerm . "%");
//                },
            ],
            [
                'name' => 'descricao',
                'label' => 'Subcategoria',
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
        ];

        return $colunas;
    }

    public function Campos($orgao, $categorias)
    {
        $campos = [
            [ // select_from_array
                'name' => 'orgao_id',
                'label' => "Órgão",
                'type' => 'select_from_array',
                'options' => $orgao,
                'allows_null' => false,
            ],
            [ // select_from_array
                'name' => 'categoria_id',
                'label' => "Categoria",
                'type' => 'select2_from_array',
                'options' => $categorias,
                'allows_null' => true,
            ],
            [
                'name' => 'descricao',
                'label' => 'Subcategoria',
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
            ]

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
