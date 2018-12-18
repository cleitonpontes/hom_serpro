<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\Authorizes;
use App\Jobs\UserMailPasswordJob;
use App\Models\BackpackUser;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UsuarioRequest as StoreRequest;
use App\Http\Requests\UsuarioRequest as UpdateRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class UsuarioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UsuarioCrudController extends CrudController
{
    use Authorizes;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BackpackUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/usuario');
        $this->crud->setEntityNameStrings('usuario', 'usuarios');
        $this->crud->enableExportButtons();
        $this->crud->enableAjaxTable();
//        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
//        $this->crud->addButtonFromView('line', 'moreusuario', 'moreusuario', 'end');
        $this->crud->denyAccess('delete');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Columns
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $this->crud->setColumns([
            [
                'name' => 'cpf',
                'label' => 'CPF',
                'type' => 'text',
            ],
            [
                'name' => 'name',
                'label' => 'Nome',
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
            ],
            [
                'name' => 'getUGPrimaria',
                'label' => 'UG Primária', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUGPrimaria', // the method in your Model
                'orderable' => true,
//                'searchLogic' => function ($query, $column, $searchTerm) {
//                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
//                        $q->where('nome', 'like', '%' . $searchTerm . '%');
//                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
//                            ->orWhereDate('depart_at', '=', date($searchTerm));
//                    });
//                },
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
        ]);


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Fields
        |--------------------------------------------------------------------------
        */
        $ugs = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->where('tipo', '=', 'E')
            ->where('situacao', '=', true)
            ->orderBy('codigo', 'asc')
            ->pluck('nome', 'id')
            ->toArray();

        $this->crud->addFields([
            [
                'name' => 'cpf',
                'label' => 'CPF',
                'type' => 'cpf',
                'tab' => 'Dados Pessoais',
            ],
            [
                'name' => 'name',
                'label' => 'Nome Completo',
                'type' => 'text',
                'tab' => 'Dados Pessoais',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ],
            [
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'tab' => 'Dados Pessoais',
            ],
            [ // select2_from_array
                'name' => 'ugprimaria',
                'label' => 'UG Primária',
                'type' => 'select2_from_array',
                'options' => $ugs,
                'allows_null' => true,
                'tab' => 'Outros',
                'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select2_from_array
                'name' => 'ugprimaria',
                'label' => 'UG Primária',
                'type' => 'select2_from_array',
                'options' => $ugs,
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
                'model' => "App\Models\Unidade", // foreign key model
                'allows_null' => true,
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'select_all' => true,
                'tab' => 'Outros',
                'options' => (function ($query) {
                    return $query->orderBy('codigo', 'ASC')->where('tipo', '=', 'E')->get();
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
                    return $query->orderBy('name', 'ASC')->get();
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
        ]);


        // add asterisk for fields that are required in UsuarioRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();

    }

    public function store(StoreRequest $request)
    {
//        $this->authorizePermissions(['administracao_editar_usuario']);

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
//        $this->authorizePermissions(['administracao_editar_usuario']);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
