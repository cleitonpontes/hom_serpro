<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\UserMailPasswordJob;
use App\Models\BackpackUser;
use App\Models\Orgao;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UsuarioOrgaoRequest as StoreRequest;
use App\Http\Requests\UsuarioOrgaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use function foo\func;
use Illuminate\Support\Facades\DB;

/**
 * Class UsuarioOrgaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UsuarioOrgaoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        if (!backpack_user()->hasRole('Administrador Órgão')) { //alterar para novo grupo de Administrador Orgão
            abort('403', config('app.erro_permissao'));
        }

        $orgaos = Orgao::whereHas('unidades', function ($u) {
            $u->whereHas('users', function ($us) {
                $us->where('cpf', backpack_user()->cpf);
            })->orWhereHas('user', function ($usu) {
                $usu->where('cpf', backpack_user()->cpf);
            });
        })->pluck('id')->toArray();

        $unidades_orgao = Unidade::whereIn('orgao_id',$orgaos)
            ->where('tipo','E')
            ->pluck('id')->toArray();

        $this->crud->setModel('App\Models\BackpackUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/usuarioorgao');
        $this->crud->setEntityNameStrings('Usuário Órgão(s)', 'Usuários Órgão(s)');
        $this->crud->addClause('leftjoin', 'unidades', 'unidades.id', '=', 'users.ugprimaria');
        $this->crud->addClause('select', 'users.*');
        $this->crud->query->where(function ($q) use ($unidades_orgao) {
            $q->whereHas('unidade', function ($u) use ($unidades_orgao) {
                $u->whereHas('users', function ($t) use ($unidades_orgao) {
                    $t->whereIn('unidade_id', $unidades_orgao);
                });
                $u->orWhereIn('id', $unidades_orgao);
            })
                ->orWhere('users.situacao', '=', 0)
                ->orWhere(function ($q) {
                    $q->whereNull('users.ugprimaria')
                        ->where('users.situacao', '=', 1);
                });
        });

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');

        (backpack_user()->can('usuarioorgao_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('usuarioorgao_editar')) ? $this->crud->allowAccess('update') : null;
//        (backpack_user()->can('usuarioorgao_deletar')) ? $this->crud->allowAccess('delete') : null;

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
            ->whereIn('id', $unidades_orgao)
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'id')
            ->toArray();

        $campos = $this->Campos($ugs, $unidades_orgao);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in UsuarioOrgaoRequest
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
                    $query->orWhere('users.cpf', 'like', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                },
            ],
            [
                'name' => 'name',
                'label' => 'Nome',
                'type' => 'text',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('users.name', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('users.email', 'like', "%" . utf8_encode(utf8_decode(strtolower($searchTerm))) . "%");
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
                'label' => 'UG/UASG Padrão',
                'type' => 'model_function',
                'function_name' => 'getUGPrimaria', // the method in your Model
                'orderable' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('unidades.codigo', 'like', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                    $query->orWhere('unidades.nomeresumido', 'like', "%" . utf8_encode(utf8_decode(strtoupper($searchTerm))) . "%");
                },
            ],
            [ // n-n relationship (with pivot table)
                'label' => trans('backpack::permissionmanager.roles'), // Table column heading
                'type' => 'select_multiple',
                'name' => 'roles', // the method that defines the relationship in your Model
                'entity' => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => config('permission.models.role'), // foreign key model
            ],
//            [ // n-n relationship (with pivot table)
//                'label' => trans('backpack::permissionmanager.extra_permissions'), // Table column heading
//                'type' => 'select_multiple',
//                'name' => 'permissions', // the method that defines the relationship in your Model
//                'entity' => 'permissions', // the method that defines the relationship in your Model
//                'attribute' => 'name', // foreign key attribute that is shown to user
//                'model' => config('permission.models.permission'), // foreign key model
//            ],
        ];

        return $colunas;
    }

    public function campos($ugs, $unidades_orgao)
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
                'label' => 'UG/UASG Padrão',
                'type' => 'select2_from_array',
                'options' => $ugs,
                'allows_null' => true,
                'tab' => 'Outros',
                'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => 'Demais UGs/UASGs',
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
                'options' => (function ($query) use ($unidades_orgao) {
                    return $query->orderBy('codigo', 'ASC')
                        ->where('tipo', '=', 'E')
                        ->whereIn('id', $unidades_orgao)
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

        if ($usuario->hasRole('Administrador Órgão') or $usuario->hasRole('Administrador')) {
            \Alert::error('Sem permissão para alterar este Usuário!')->flash();
            return redirect()->back();
        } else {
            // your additional operations before save here
            if ($request->input('situacao') == false) { // 0 = false = inativo
                $request->request->set('ugprimaria', null);
                $request->request->set('unidades', null);
                $request->request->set('roles', null);
            }
            $redirect_location = parent::updateCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            return $redirect_location;
        }
    }

}
