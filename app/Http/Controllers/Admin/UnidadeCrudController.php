<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Execfin\EmpenhoCrudController;
use App\Jobs\AlertaContratoJob;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Orgao;
use App\Models\OrgaoSuperior;
use App\Models\Unidade;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UnidadeRequest as StoreRequest;
use App\Http\Requests\UnidadeRequest as UpdateRequest;
use Exception;

/**
 * Class UnidadeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UnidadeCrudController extends CrudController
{
    /**
     * @throws Exception
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

            $this->crud->addClause('orderBy', 'nomeresumido');

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
                'name' => 'codigosiasg',
                'label' => 'UASG SIASG', // Table column heading
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
                'label' => 'UG SIAFI', // Table column heading
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
//            [
//                'name' => 'codigosiasg',
//                'label' => 'UASG SIASG', // Table column heading
//                'type' => 'text',
//                'orderable' => true,
//                'visibleInTable' => true, // no point, since it's a large text
//                'visibleInModal' => true, // would make the modal too big
//                'visibleInExport' => true, // not important enough
//                'visibleInShow' => true, // sure, why not
////                'searchLogic' => function ($query, $column, $searchTerm) {
////                    $query->orWhereHas('unidade_id', function ($q) use ($column, $searchTerm) {
////                        $q->where('nome', 'like', '%' . $searchTerm . '%');
////                        $q->where('codigo', 'like', '%' . $searchTerm . '%');
////                            ->orWhereDate('depart_at', '=', date($searchTerm));
////                    });
////                },
//            ],
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
                'name' => 'sisg',
                'label' => 'Sisg',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'getUF',
                'label' => 'UF',
                'type' => 'model_function',
                'function_name' => 'getUF',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'getMunicipio',
                'label' => 'Município',
                'type' => 'model_function',
                'function_name' => 'getMunicipio',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'esfera',
                'label' => 'Esfera',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'poder',
                'label' => 'Poder',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'tipo_adm',
                'label' => 'Administração',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'aderiu_siasg',
                'label' => 'SIASG',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'utiliza_siafi',
                'label' => 'SIAFI',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],
            [
                'name' => 'codigo_siorg',
                'label' => 'SIORG',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
//                'options' => [0 => 'Inativo', 1 => 'Ativo']
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
            ]

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
                'name' => 'sisg',
                'label' => "Sisg",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
            ],
            [
                'name' => 'uf',
                'label' => "Estado",
                'type' => 'select2_from_array',
                'options' =>
                    [
                        12 => "Acre",
                        27 => "Alagoas",
                        16 => "Amazonas",
                        13 => "Amapá",
                        29 => "Bahia",
                        23 => "Ceará",
                        53 => "Distrito Federal",
                        32 => "Espírito Santo",
                        52 => "Goiás",
                        21 => "Maranhão",
                        51 => "Mato Grosso",
                        50 => "Mato Grosso do Sul",
                        31 => "Minas Gerais",
                        15 => "Pará",
                        25 => "Paraíba",
                        41 => "Paraná",
                        26 => "Pernambuco",
                        22 => "Piauí",
                        33 => "Rio de Janeiro",
                        24 => "Rio Grande do Norte",
                        43 => "Rondônia",
                        11 => "Rio Grande do Sul",
                        14 => "Roraima",
                        42 => "Santa Catarina",
                        35 => "Sergipe",
                        28 => "São Paulo",
                        17 => "Tocantins",
                        99 => "Exterior"
                    ],
                'allows_null' => true,
                'default' => $this->estadoId()
            ],
            [ // select_from_array
                'name' => 'municipio_id', // the column that contains the ID of that connected entity
                'label' => "Municipio", // Table column heading
                'type' => 'select2_from_ajax',
                'model' => 'App\Models\Municipio',
                'entity' => 'municipio', // the method that defines the relationship in your Model
                'attribute' => 'nome', // foreign key attribute that is shown to user
                'data_source' => url('api/municipios'), // url to controller search function (with /{id} should return model)
                'placeholder' => 'Selecione...', // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies' => ['uf'], // when a dependency changes, this select2 is reset to null
                'method' => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST)
            ],
            [ // select_from_array
                'name' => 'esfera',
                'label' => "Esfera",
                'type' => 'select_from_array',
                'options' => ['Estadual' => 'Estadual', 'Federal' => 'Federal'],
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'poder',
                'label' => "Poder",
                'type' => 'select_from_array',
                'options' => ['Executivo' => 'Executivo',
                    'Judiciário' => 'Judiciário',
                    'Legislativo' => 'Legislativo'],
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'tipo_adm',
                'label' => "Administração",
                'type' => 'select_from_array',
                'options' => ['ADMINISTRAÇÃO DIRETA' => 'ADMINISTRAÇÃO DIRETA',
                    'ADMINISTRAÇÃO DIRETA ESTADUAL' => 'ADMINISTRAÇÃO DIRETA ESTADUAL',
                    'AUTARQUIA' => 'AUTARQUIA',
                    'ECONOMIA MISTA' => 'ECONOMIA MISTA',
                    'EMPRESA PÚBLICA COM. E FIN.' => 'EMPRESA PÚBLICA COM. E FIN.',
                    'FUNDAÇÃO' => 'FUNDAÇÃO',
                    'FUNDOS' => 'FUNDOS'],
                'allows_null' => true,
            ],
            [ // select_from_array
                'name' => 'aderiu_siasg',
                'label' => "Aderiu SIASG",
                'type' => 'select_from_array',
                'options' => ['Não', 'Sim'],
                'allows_null' => false,
                'default' => 1,
            ],
            [ // select_from_array
                'name' => 'utiliza_siafi',
                'label' => "Utiliza SIAFI",
                'type' => 'select_from_array',
                'options' => ['Não', 'Sim'],
                'allows_null' => false,
                'default' => 1,
            ],
            [ // select_from_array
                'name' => 'codigo_siorg',
                'label' => "Codigo Siorg",
                'type' => 'text',
                'allows_null' => true
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
        $this->crud->removeColumn('municipio_id');

        return $content;
    }

    public function executaRotinaAlertaMensal()
    {
        $alerta = new AlertaContratoJob();
        $alerta->extratoMensal();

        if (backpack_user()) {
            Alert::success('Alerta Mensal executado com Sucesso!')->flash();
            return redirect('/admin/unidade');
        }
    }

    public function executaAtualizacaoCadastroUnidade()
    {
        if (!backpack_user()->hasRole('Administrador')) {
            abort('403', config('app.erro_permissao'));
        }

        $url = config('migracao.api_sta') . '/api/estrutura/unidades';

        $funcao = new AdminController();

        $dados = $funcao->buscaDadosUrlMigracao($url);

        foreach ($dados as $dado) {

            $unidade = Unidade::where('codigo', $dado['codigo'])
                ->first();

            if (!isset($unidade->codigo)) {

                $orgao = Orgao::where('codigo', $dado['orgao'])
                    ->first();

                if (isset($orgao->id)) {
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

            } else {
                if ($unidade->nome != $dado['nome'] or $unidade->nomeresumido != $dado['nomeresumido'] or $unidade->orgao->codigo != $dado['orgao']) {

                    $orgao = Orgao::where('codigo', $dado['orgao'])
                        ->first();

                    if (isset($orgao->id)) {
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

    private function estadoId()
    {
        $estado = '';
        if ($this->crud->getActionMethod() === 'edit') {
            $estado = $this->crud->getEntry($this->crud->getCurrentEntryId())->municipio->estado->id;
        }
        return $estado;
    }

}
