<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Execfin\EmpenhoCrudController;
use App\Jobs\AlertaContratoJob;
use App\Models\Orgao;
use App\Models\OrgaoSuperior;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UnidadeRequest as StoreRequest;
use App\Http\Requests\UnidadeRequest as UpdateRequest;

/**
 * Class UnidadeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UnidadeCrudController extends CrudController
{
    /**
     * @throws \Exception
     */
    public function setup()
    {
        if (backpack_user()->hasRole('Administrador') or backpack_user()->hasRole('Administrador Órgão') or backpack_user()->hasRole('Administrador Unidade')) {

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Basic Information
            |--------------------------------------------------------------------------
            */
            $this->crud->setModel('App\Models\Unidade');
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/unidade');
            $this->crud->setEntityNameStrings('Unidade', 'Unidades');
            $this->crud->enableExportButtons();
            $this->crud->addButtonFromView('line', 'moreunidade', 'moreunidade', 'end');
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');
            $this->crud->allowAccess('show');

            (backpack_user()->hasRole('Administrador')) ? $this->crud->addButtonFromView('top', 'atualizaunidade',
                'atualizaunidade', 'end') : null;

            (backpack_user()->can('unidade_inserir')) ? $this->crud->allowAccess('create') : null;
            (backpack_user()->can('unidade_editar')) ? $this->crud->allowAccess('update') : null;
            (backpack_user()->can('unidade_deletar')) ? $this->crud->allowAccess('delete') : null;

            (backpack_user()->can('executa_rotina_alerta_mensal')) ? $this->crud->addButtonFromView('top', 'rotinaalertamensal',
                'rotinaalertamensal', 'end') : null;

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Configuration
            |--------------------------------------------------------------------------
            */

            // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

            $colunas = $this->Colunas();
            $this->crud->addColumns($colunas);

            $orgaos = Orgao::where('situacao', '=', true)->pluck('nome', 'id')->toArray();

            $campos = $this->Campos($orgaos);
            $this->crud->addFields($campos);

            // add asterisk for fields that are required in UnidadeRequest
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
                'name' => 'getOrgao',
                'label' => 'Órgão', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getOrgao', // the method in your Model
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
                'name' => 'gestao',
                'label' => 'Gestão', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
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
                'name' => 'nome',
                'label' => 'Nome', // Table column heading
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
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
                'name' => 'nomeresumido',
                'label' => 'Nome Resumido', // Table column heading
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
                'name' => 'telefone',
                'label' => 'Telefone', // Table column heading
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
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model
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

    public function Campos($orgaos)
    {

        $campos = [
            [ // select_from_array
                'name' => 'orgao_id',
                'label' => "Órgão",
                'type' => 'select2_from_array',
                'options' => $orgaos,
                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'codigo',
                'label' => "Código SIAFI",
                'type' => 'unidade',
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'gestao',
                'label' => "Gestão",
                'type' => 'gestao',
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'codigosiasg',
                'label' => "Código SIASG",
                'type' => 'unidade',
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'nome',
                'label' => "Nome",
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)",
                ]
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'nomeresumido',
                'label' => "Nome Resumido",
                'type' => 'text',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)",
                    'maxlength' => "19"
                ]
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'telefone',
                'label' => "Telefone Fixo",
                'type' => 'telefone',
//                'attributes' => [
//                    'onkeyup' => "maiuscula(this)",
//                    'maxlength' => "10"
//                ]
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'tipo',
                'label' => "Tipo",
                'type' => 'select_from_array',
                'options' => [
                    'C' => 'Controle',
                    'E' => 'Executora',
                    'S' => 'Setorial Contábil',
                ],
                'allows_null' => true,
                'default' => 'E',
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

        $this->crud->removeColumn('orgao_id');
        $this->crud->removeColumn('tipo');

        return $content;
    }

    public function executaRotinaAlertaMensal()
    {
        $alerta = new AlertaContratoJob();
        $alerta->extratoMensal();

        if (backpack_user()) {
            \Alert::success('Alerta Mensal executado com Sucesso!')->flash();
            return redirect('/admin/unidade');
        }
    }

    public function executaAtualizacaoCadastroUnidade()
    {
        if (!backpack_user()->hasRole('Administrador')) {
            abort('403', config('app.erro_permissao'));
        }

        $url = config('migracao.api_sta'). '/api/estrutura/unidades';

        $funcao = new EmpenhoCrudController;

        $dados = $funcao->buscaDadosUrl($url);

        foreach ($dados as $dado) {

            $unidade = Unidade::where('codigo',$dado['codigo'])
                ->first();

            if(!isset($unidade->codigo)){

                $orgao = Orgao::where('codigo',$dado['orgao'])
                    ->first();

                if(isset($orgao->id)){
                    $novo = new Unidade();
                    $novo->orgao_id = $orgao->id;
                    $novo->codigo = $dado['codigo'];
                    $novo->gestao = $dado['gestao'];
                    $novo->codigosiasg = ($dado['funcao'] == 'Executora') ? $dado['codigo'] : '';
                    $novo->nome = $dado['nome'];
                    $novo->nomeresumido = $dado['nomeresumido'];
                    $novo->tipo = ($dado['funcao'] == 'Executora') ? 'E' : 'C';
                    $novo->situacao = true;
                    $novo->save();
                }

            }else{
                if($unidade->nome != $dado['nome'] or $unidade->nomeresumido != $dado['nomeresumido'] or $unidade->orgao->codigo != $dado['orgao']){

                    $orgao = Orgao::where('codigo',$dado['orgao'])
                        ->first();

                    if(isset($orgao->id)) {
                        $unidade->orgao_id = $orgao->id;
                        $unidade->nome = $dado['nome'];
                        $unidade->nomeresumido = $dado['nomeresumido'];
                        $unidade->save();
                    }

                }
            }
        }

        return redirect('admin/unidade');
    }

}
