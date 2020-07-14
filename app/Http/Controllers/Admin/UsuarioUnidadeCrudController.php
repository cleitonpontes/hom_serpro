<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\UserMailPasswordJob;
use App\Models\BackpackUser;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UsuarioUnidadeRequest as StoreRequest;
use App\Http\Requests\UsuarioUnidadeRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class UsuarioUnidadeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UsuarioUnidadeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        if (!backpack_user()->hasRole('Administrador Unidade')) { //alterar para novo grupo de Administrador Orgão
            abort('403', config('app.erro_permissao'));
        }

        $unidade_user = Unidade::find(session()->get('user_ug_id'));

        $this->crud->setModel('App\Models\BackpackUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/usuariounidade');




        // $this->crud->setEntityNameStrings('Usuário Unidade: ' . $unidade_user->codigo, 'Usuários Unidade: ' . $unidade_user->codigo);
        // $this->crud->addClause('select', 'users.*');
        // $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'users.ugprimaria');
        // $this->crud->addClause('where', 'users.unidade_id', '=', $unidade_user->id);





        $this->crud->setEntityNameStrings('Usuário Unidade: ' . $unidade_user->codigo, 'Usuários Unidade: ' . $unidade_user->codigo);
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'users.ugprimaria');
        $this->crud->addClause('where', 'users.ugprimaria', '=', $unidade_user->id);





        // $this->crud->addClause('whereHas', 'unidades', function ($q) use ($unidade_user) {
        //     $q->where('unidade_id', $unidade_user->id);
        //     $q->orWhere('ugprimaria', $unidade_user->id);
        // });
        // $this->crud->addClause('orWhere', 'ugprimaria', '=', $unidade_user->id);
        // $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'users.ugprimaria');
        // $this->crud->addClause('where', 'users.unidade_id', '=', $unidade_user->id);



        $this->crud->addClause('select', 'users.*');


        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');

        (backpack_user()->can('usuariounidade_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('usuariounidade_editar')) ? $this->crud->allowAccess('update') : null;
//        (backpack_user()->can('usuariounidade_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        $ugs = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->where('tipo', '=', 'E')
            ->where('situacao', '=', true)
            ->where('id', $unidade_user->id)
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'id')
            ->toArray();


        $campos = $this->Campos($ugs, $unidade_user->id);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in UsuarioUnidadeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function colunas()
    {
        $colunas = [
            [
                'name' => 'cpf',
                'label' => 'CPF',
                'type' => 'text',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('users.cpf', 'ilike', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                },
            ],
            [
                'name' => 'name',
                'label' => 'Nome',
                'type' => 'text',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('users.name', 'ilike', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                },
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('users.email', 'ilike', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                },
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'options' => [0 => 'Inativo', 1 => 'Ativo'],
                'visibleInTable' => false,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    if (strtolower($searchTerm) == 'inativo') {
                        $query->orWhere('users.situacao', 0);
                    }

                    if (strtolower($searchTerm) == 'ativo') {
                        $query->orWhere('users.situacao', 1);
                    }
                }
            ],
            [
                'name' => 'getUGPrimaria',
                'label' => 'UG Primária', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUGPrimaria', // the method in your Model
                'orderable' => true,
            ],
            [ // n-n relationship (with pivot table)
                'label' => trans('backpack::permissionmanager.roles'), // Table column heading
                'type' => 'select_multiple',
                'name' => 'roles', // the method that defines the relationship in your Model
                'entity' => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => config('permission.models.role'), // foreign key model
            ],
        ];

        return $colunas;
    }

    public function campos($ug, $unidade)
    {
        $campos = [
            [
                'name' => 'cpf',
                'label' => 'CPF',
                'type' => 'cpf',
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-3'
//                ],
                'tab' => 'Dados Pessoais',
            ],
            [
                'name' => 'name',
                'label' => 'Nome Completo',
                'type' => 'text',
                'tab' => 'Dados Pessoais',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
//                'wrapperAttributes' => [
//                    'class' => 'form-group col-md-3'
//                ],
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'tab' => 'Dados Pessoais',
            ],
            [
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
                'tab' => 'Dados Pessoais'
            ],
            [ // select2_from_array
                'name' => 'ugprimaria',
                'label' => 'UG Primária',
                'type' => 'select2_from_array',
                'options' => $ug,
                'allows_null' => true,
                'tab' => 'Outros',
                'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => 'UG´s Secundárias',
                'type' => 'select2_multiple',
                'name' => 'unidades', // the method that defines the relationship in your Model
                'entity' => 'unidades', // the method that defines the relationship in your Model
                'attribute' => 'codigo', // foreign key attribute that is shown to user
                'attribute2' => 'nomeresumido', // foreign key attribute that is shown to user
                'attribute_separator' => ' - ', // foreign key attribute that is shown to user
                'model' => "App\Models\Unidade", // foreign key model
                'allows_null' => true,
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'select_all' => true,
                'tab' => 'Outros',
                'options' => (function ($query) use ($unidade) {
                    return $query->orderBy('codigo', 'ASC')
                        ->where('tipo', '=', 'E')
                        ->where('id', $unidade)
                        ->get();
                }),
            ],
            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => 'Grupos de Usuário',
                'type' => 'select2_multiple',
                'name' => 'roles', // the method that defines the relationship in your Model
                'entity' => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => config('permission.models.role'), // foreign key model
                'allows_null' => true,
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'select_all' => true,
                'tab' => 'Outros',
                'options' => (function ($query) {
                    return $query->orderBy('name', 'ASC')
                        ->where('name', '<>', 'Administrador')
                        ->where('name', '<>', 'Administrador Órgão')
                        ->where('name', '<>', 'Administrador Unidade')
                        ->get();
                }),
            ],
//            [
//                // two interconnected entities
//                'label' => trans('backpack::permissionmanager.user_role_permission'),
//                'field_unique_name' => 'user_role_permission',
//                'type' => 'checklist_dependency',
//                'name' => 'roles_and_permissions',
//                'tab' => 'Outros',// the methods that defines the relationship in your Model
//
//                'subfields' => [
//                    'primary' => [
//                        'label' => trans('backpack::permissionmanager.roles'),
//                        'name' => 'roles', // the method that defines the relationship in your Model
//                        'entity' => 'roles', // the method that defines the relationship in your Model
//                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
//                        'attribute' => 'name', // foreign key attribute that is shown to user
//                        'model' => config('permission.models.role'), // foreign key model
//                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
//                        'number_columns' => 3, //can be 1,2,3,4,6
//                    ],
//                    'secondary' => [
//                        'label' => ucfirst(trans('backpack::permissionmanager.permission_singular')),
//                        'name' => 'permissions', // the method that defines the relationship in your Model
//                        'entity' => 'permissions', // the method that defines the relationship in your Model
//                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
//                        'attribute' => 'name', // foreign key attribute that is shown to user
//                        'model' => config('permission.models.permission'), // foreign key model
//                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
//                        'number_columns' => 3, //can be 1,2,3,4,6
//                    ],
//                ],
//            ],
        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $chars = '0123456789';
        $max = strlen($chars) - 1;
        $senha = "NOVA";
        for ($i = 0; $i < 4; $i++) {
            $senha .= $chars{mt_rand(0, $max)};
        }

        $request->request->set('password', bcrypt($senha));

        $dados = [
            'email' => $request->input('email'),
            'cpf' => $request->input('cpf'),
            'nome' => $request->input('name'),
            'senha' => $senha,
        ];

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);

        $usuario = BackpackUser::where('cpf', '=', $dados['cpf'])->first();

        if ($usuario) {
            UserMailPasswordJob::dispatch($usuario, $dados);
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $usuario = BackpackUser::where('cpf', '=', $request->input('cpf'))->first();

        if ($usuario->hasRole('Administrador Órgão') or $usuario->hasRole('Administrador') or $usuario->hasRole('Administrador Unidade')) {
            \Alert::error('Sem permissão para alterar este Usuário!')->flash();
            return redirect()->back();
        }else{
            // your additional operations before save here
            $redirect_location = parent::updateCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            return $redirect_location;
        }
    }
}
