<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orgao;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UsuarioOrgaoRequest as StoreRequest;
use App\Http\Requests\UsuarioOrgaoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use function foo\func;

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

        $unidade_user = Unidade::find(session()->get('user_ug_id'));

        $orgao = Orgao::find($unidade_user->orgao_id);
        $unidades_orgao = Unidade::where('orgao_id',$orgao->id)
            ->pluck('id')->toArray();


        $this->crud->setModel('App\Models\BackpackUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/usuarioorgao');
        $this->crud->setEntityNameStrings('Usuário Órgão: ' . $orgao->codigo, 'Usuários Órgão: ' . $orgao->codigo);
        $this->crud->addClause('WhereHas', 'unidades', function ($q) use ($unidades_orgao) {
            $q->whereIn('unidade_id',$unidades_orgao);
        });
        foreach ($unidades_orgao as $item){
            $this->crud->addClause('orwhere', 'ugprimaria', '=', $item);
        }




        $this->crud->enableExportButtons();
//        $this->crud->denyAccess('create');
//        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');

//        (backpack_user()->can('usuarioorgao_inserir')) ? $this->crud->allowAccess('create') : null;
//        (backpack_user()->can('usuarioorgao_editar')) ? $this->crud->allowAccess('update') : null;
//        (backpack_user()->can('usuarioorgao_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in UsuarioOrgaoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
