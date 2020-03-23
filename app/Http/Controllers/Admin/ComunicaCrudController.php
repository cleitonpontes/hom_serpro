<?php

namespace App\Http\Controllers\Admin;

use App\Models\BackpackUser;
use App\Models\Comunica;
use App\Models\Orgao;
use App\Models\Unidade;
use App\Repositories\Comunica as Repo;
use App\Repositories\OrgaoSuperior as RepoOrgaoSuperior;
use App\Repositories\Unidade as RepoUnidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ComunicaRequest as StoreRequest;
use App\Http\Requests\ComunicaRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class ComunicaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ComunicaCrudController extends CrudController
{

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Comunica');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/comunica');
        $this->crud->setEntityNameStrings('Comunica', 'Comunica');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('comunica_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('comunica_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('comunica_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumns($this->Colunas());
        $this->crud->addFields($this->Campos());

        // add asterisk for fields that are required in ComunicaRequest
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

    public function show($id)
    {

        $content = parent::show($id);

        $this->crud->removeColumns([
            'orgao_id',
            'unidade_id',
            'role_id',
            'mensagem',
            'situacao',
        ]);

        return $content;
    }

    /**
     * Retorna array de colunas a serem exibidas no grid de listagem
     *
     * @return array
     */
    public function Colunas()
    {

        return [
            [
                'name' => 'getOrgao',
                'label' => 'Órgão',
                'type' => 'model_function',
                'function_name' => 'getOrgao',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getGrupo',
                'label' => 'Grupo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getGrupo', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'assunto',
                'label' => 'Assunto', // Table column heading
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getMensagem',
                'label' => 'Mensagem', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getMensagem', // the method in your Model
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
//            [
//                'name' => 'mensagem',
//                'label' => 'Mensagem', // Table column heading
//                'type' => 'text',
//                'limit' => 1000,
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
//            ],
            [
                'name' => 'anexos',
                'label' => 'Anexos', // Table column heading
                'type' => 'upload_multiple',
                'disk' => 'local',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Situação', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSituacao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ]
        ];

    }

    /**
     * Retorna array dos campos para exibição no form
     *
     * @return array
     */
    public function Campos()
    {

        $repo = new Repo();
        $repoOrgaosuperior = new RepoOrgaoSuperior();
        $repoUnidade = new RepoUnidade();

        $grupos = Role::pluck('name', 'id')->toArray();
        $orgaos = $repoOrgaosuperior->getOrgaosParaCombo();
        $unidades = $repoUnidade->getUnidadesParaComboPorPerfil(session('user_orgao_id'), session('user_ug_id'), $repo->getOrgao(), $repo->getUnidade());

        $campos = array();

        if (backpack_user()->hasRole('Administrador')) {
            $campos[] = [
                'name' => 'orgao_id',
                'label' => "Órgão",
                'type' => 'select2_from_array',
                'options' => $orgaos,
                'placeholder' => "Todos",
                'allows_null' => true
            ];
        }

        if (backpack_user()->hasRole('Administrador Órgão')) {
            $campos[] = [
                'name' => 'orgao_id',
                'label' => "Órgão",
                'type' => 'hidden',
                'value' => session('user_orgao_id'),
                'allows_null' => true
            ];
        }

        $campos[] = [
            'label' => "Unidade",
            'type' => "select2_from_ajax",
            'name' => 'unidade_id',
            'model' => "App\Models\Unidade",
            'attribute' => "codigo",
            'entity' => 'unidade',
            'process_results_template' => 'gescon.process_results_unidade_codigo_descricao',
            'data_source' => url("api/unidade"),
            'placeholder' => "Selecione...",
            'minimum_input_length' => 2
        ];
        */

        $campos[] = [
            'name' => 'role_id',
            'label' => "Grupos",
            'type' => 'select2_from_array',
            'options' => $grupos,
            'placeholder' => "Todos",
            'allows_null' => true
        ];

        $campos[] = [
            'name' => 'assunto',
            'label' => "Assunto",
            'type' => 'text',
            'attributes' => [
                'onkeyup' => "maiuscula(this)"
            ]
        ];

        $campos[] = [
            'name' => 'mensagem',
            'label' => "Mensagem",
            'type' => 'ckeditor'
        ];

        $campos[] = [
            'name' => 'anexos',
            'label' => 'Anexos',
            'type' => 'upload_multiple',
            'upload' => true,
            'disk' => 'local'
        ];

        $campos[] = [ // select_from_array
            'name' => 'situacao',
            'label' => "Situação",
            'type' => 'select_from_array',
            'options' => $this->retornaSituacoesComunica(),
            'allows_null' => true
        ];

        return $campos;
    }

    /**
     * Retorna array com todas as situações da comunicação
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaSituacoesComunica()
    {

        $repo = new Repo();
        return $repo->getSituacoes();
    }

    /**
     * Retorna Orgão conforme $idUnidade
     *
     * @param $idUnidade
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaOrgaoPorUnidade($idUnidade)
    {

        $repo = new Repo();
        return $repo->retornaOrgaoPorUnidade($idUnidade);
    }

}
