<?php

namespace App\Http\Controllers\Execfin;

use Alert;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Transparencia\IndexController;
use App\Http\Traits\Busca;
use App\Http\Traits\BuscaCodigoItens;
use App\Http\Traits\Formatador;
use App\Jobs\AtualizaNaturezaDespesasJob;
use App\Jobs\AtualizasaldosmpenhosJobs;
use App\Jobs\EnviaEmpenhoSiasgJob;
use App\Jobs\MigracaoCargaEmpenhoJob;
use App\Jobs\MigracaoempenhoJob;
use App\Jobs\MigracaoRpJob;
use App\Models\BackpackUser;
use App\Models\Codigoitem;
use App\Models\CompraItemUnidade;
use App\Models\DevolveMinutaSiasg;
use App\Models\Empenho;
use App\Models\Empenhodetalhado;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\Naturezadespesa;
use App\Models\Naturezasubitem;
use App\Models\Planointerno;
use App\Models\SfItemEmpenho;
use App\Models\SfOrcEmpenhoDados;
use App\Models\Unidade;
use App\STA\ConsultaApiSta;
use App\XML\ApiSiasg;
use App\XML\Execsiafi;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmpenhoRequest as StoreRequest;
use App\Http\Requests\EmpenhoRequest as UpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class EmpenhoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmpenhoCrudController extends CrudController
{
    use Busca, BuscaCodigoItens, Formatador;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Empenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/execfin/empenho');
        $this->crud->setEntityNameStrings('empenho', 'empenhos');

        $this->crud->addClause('select', 'empenhos.*');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'empenhos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'empenhos.unidade_id');
        $this->crud->addClause('join', 'naturezadespesa', 'naturezadespesa.id', '=', 'empenhos.naturezadespesa_id');
        $this->crud->addClause('where', 'empenhos.unidade_id', '=', session()->get('user_ug_id'));
//        $this->crud->addClause('where', DB::raw('left(empenhos."numero", 4)'), '=', config('app.ano_minuta_empenho'));
//        $this->crud->addClause('orWhere', 'empenhos.rp', '=', true);
        $this->crud->orderBy('empenhos.updated_at', 'desc');

        (backpack_user()->can('migracao_empenhos')) ? $this->crud->addButtonFromView(
            'top',
            'migrarempenho',
            'migrarempenho',
            'end'
        ) : null;

        (backpack_user()->can('atualizacao_saldos_empenhos')) ? $this->crud->addButtonFromView(
            'top',
            'atualizasaldosempenhos',
            'atualizasaldosempenhos',
            'end'
        ) : null;

        $this->crud->addButtonFromView('line', 'moreempenho', 'moreempenho', 'end');

        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('empenho_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('empenho_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('empenho_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $campos = $this->Campos($unidade);

        $this->crud->addFields($campos);

        // add asterisk for fields that are required in EmpenhoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
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
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('unidades.codigo', 'like', "%$searchTerm%");
                    $query->orWhere('unidades.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('unidades.nomeresumido', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'numero',
                'label' => 'Número Empenho',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFornecedor', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'getPi',
                'label' => 'Plano Interno', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getPi', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('planointerno.codigo', 'like', "%" . strtoupper($searchTerm) . "%");
//                    $query->orWhere('planointerno.descricao', 'like', "%" . strtoupper($searchTerm) . "%");
//                },
            ],
            [
                'name' => 'getNatureza',
                'label' => 'Natureza Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getNatureza', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('naturezadespesa.codigo', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('naturezadespesa.descricao', 'like', "%" . strtoupper($searchTerm) . "%");
                },
            ],
            [
                'name' => 'formatVlrEmpenhado',
                'label' => 'Empenhado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrEmpenhado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatVlraLiquidar',
                'label' => 'a Liquidar', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlraLiquidar', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatVlrLiquidado',
                'label' => 'Liquidado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrLiquidado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatVlrPago',
                'label' => 'Pago', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrPago', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatVlrRpInscrito',
                'label' => 'RP Inscrito', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpInscrito', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatVlrRpaLiquidar',
                'label' => 'RP a Liquidar', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpaLiquidar', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatVlrRpLiquidado',
                'label' => 'RP Liquidado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpLiquidado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],
            [
                'name' => 'formatVlrRpPago',
                'label' => 'RP Pago', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRpPago', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },
            ],

        ];

        return $colunas;
    }

    public function Campos($unidade)
    {
        $campos = [
            [ // select_from_array
                'name' => 'unidade_id',
                'label' => "Unidade Gestora",
                'type' => 'select2_from_array',
                'options' => $unidade,
                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'numero',
                'label' => "Número Empenho",
                'type' => 'empenho',
//                'allows_null' => false,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'label' => "Credor / Fornecedor",
                'type' => "select2_from_ajax",
                'name' => 'fornecedor_id',
                'entity' => 'fornecedor',
                'attribute' => "cpf_cnpj_idgener",
                'attribute2' => "nome",
                'process_results_template' => 'gescon.process_results_fornecedor',
                'model' => "App\Models\Fornecedor",
                'data_source' => url("api/fornecedor"),
                'placeholder' => "Selecione o fornecedor",
                'minimum_input_length' => 2,//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
//             [ // select_from_array - excluir
//                 'name' => 'fornecedor_id',
//                 'label' => "Credor / Fornecedor",
//                 'type' => 'select2_from_array',
//                 'options' => $fornecedores,
//                 'allows_null' => true,
// //                'default' => 'one',
//                 // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
//             ],
            [ // select_from_array
                'label' => "Plano Interno (PI)",
                'type' => "select2_from_ajax",
                'name' => 'planointerno_id',
                'entity' => 'planointerno',
                'attribute' => "codigo",
                'attribute2' => "descricao",
                'process_results_template' => 'gescon.process_results_planointerno',
                'model' => "App\Models\Planointerno",
                'data_source' => url("api/planointerno"),
                'placeholder' => "Selecione o Plano Interno",
                'minimum_input_length' => 2,//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'label' => "Natureza Despesa (ND)",
                'type' => "select2_from_ajax",
                'name' => 'naturezadespesa_id',
                'entity' => 'naturezadespesa',
                'attribute' => "codigo",
                'attribute2' => "descricao",
                'process_results_template' => 'gescon.process_results_planointerno',
                'model' => "App\Models\Naturezadespesa",
                'data_source' => url("api/naturezadespesa"),
                'placeholder' => "Selecione a Natureza de Despesa",
                'minimum_input_length' => 2,//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
//            [ // select_from_array
//                'name' => 'naturezadespesa_id',
//                'label' => "Natureza Despesa (ND)",
//                'type' => 'select2_from_array',
//                'options' => $naturezadespesa,
//                'allows_null' => true,
////                'default' => 'one',
//                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
//            ],

        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $fornecedores = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'id')
            ->orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();

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

        $this->crud->removeColumn('fornecedor_id');
        $this->crud->removeColumn('unidade_id');
        $this->crud->removeColumn('planointerno_id');
        $this->crud->removeColumn('naturezadespesa_id');

        $this->crud->removeColumn('empenhado');
        $this->crud->removeColumn('aliquidar');
        $this->crud->removeColumn('liquidado');
        $this->crud->removeColumn('pago');
        $this->crud->removeColumn('rpinscrito');
        $this->crud->removeColumn('rpaliquidar');
        $this->crud->removeColumn('rpliquidado');
        $this->crud->removeColumn('rppago');

        return $content;
    }

    public function executaMigracaoEmpenho()
    {
        $ano = date('Y');
        if (date('md') == '0101' or date('md') == '0102') {
            $ano = $ano - 1;
        }

        // BUSCA AS UNIDADES COM EMPENHOS CRIADOS NOS ULTIMOS
        // CINCO DIAS A PARTIR DA DATA ENVIADA (PADRÃO HOJE)
        $url = config('migracao.api_sta')
            . '/api/unidade/empenho/' . date('Y');

        $unidades = (env('APP_ENV', 'production') === 'production')
            ? $this->buscaDadosFileGetContents($url)
            : $this->buscaDadosCurl($url);

        $unidadesAtivas =
            Unidade::whereHas('contratos', function ($c) {
                $c->where('situacao', true);
            })
                ->where('situacao', true)
                ->select('codigo')
                ->whereIn('codigo', $unidades)
                ->get();

        foreach ($unidadesAtivas as $unidade) {
            MigracaoempenhoJob::dispatch($unidade->codigo, $ano)->onQueue('migracaoempenho');
        }

        // BUSCA AS UNIDADES COM RP CRIADOS NOS ULTIMOS
        // CINCO DIAS A PARTIR DA DATA ENVIADA (PADRÃO HOJE)
        $url = config('migracao.api_sta')
            . '/api/unidade/rp';

        $unidades = (env('APP_ENV', 'production') === 'production')
            ? $this->buscaDadosFileGetContents($url)
            : $this->buscaDadosCurl($url);

        $unidadesAtivas =
            Unidade::whereHas('contratos', function ($c) {
                $c->where('situacao', true);
            })
                ->where('situacao', true)
                ->select('codigo')
                ->whereIn('codigo', $unidades)
                ->get();

        foreach ($unidadesAtivas as $unidade) {
            MigracaoRpJob::dispatch($unidade->codigo)->onQueue('migracaoempenho');
        }

        if (backpack_user()) {
            Alert::success('Migração de Empenhos em Andamento!')->flash();
            return redirect('/execfin/empenho');
        }
    }

    public function executaAtualizacaoNd()
    {
//        $this->NdAtualizacao();
        AtualizaNaturezaDespesasJob::dispatch()->onQueue('atualizacaond');

        if (backpack_user()) {
            Alert::success('Atualização de ND em Andamento!')->flash();
            return redirect('/execfin/empenho');
        }
    }

    public function NdAtualizacao()
    {
        $migracao_url = config('migracao.api_sta');
        $url = $migracao_url . '/api/estrutura/naturezadespesas';

        $base = new AdminController();
        $dados = $base->buscaDadosUrlMigracao($url);

        foreach ($dados as $dado) {
            $nd = new Naturezadespesa();
            $busca_nd = $nd->buscaNaturezaDespesa($dado);

            $subitem = new Naturezasubitem();
            $busca_si = $subitem->buscaNaturezaSubitem($dado, $busca_nd);
        }

        return 'Atualização realizada com sucesso!';
    }

    public function executaAtualizaSaldosEmpenhos()
    {
        $base = new AdminController();
        $ano = $base->retornaDataMaisOuMenosQtdTipoFormato('Y', '-', '5', 'Days', date('Y-m-d'));

        $empenhos = Empenho::where(DB::raw('left(numero,4)'), $ano)
            ->orWhere('rp', true)
            ->orderBy('numero')
            ->get();

        $amb = 'PROD';
        $meses = array('', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
        $mes = $meses[(int)date('m')];//$meses[(int) $registro['mes']];

        foreach ($empenhos as $empenho) {
            $contas_contabeis = [];
            $anoEmpenho = substr($empenho->numero, 0, 4);

            if ($anoEmpenho == $ano) {
                $contas_contabeis = config('app.contas_contabeis_empenhodetalhado_exercicioatual');
            } else {
                $contas_contabeis = config('app.contas_contabeis_empenhodetalhado_exercicioanterior');
            }

            $ug = $empenho->unidade->codigo;

            $empenhodetalhes = Empenhodetalhado::where('empenho_id', '=', $empenho->id)
                ->get();

            foreach ($empenhodetalhes as $empenhodetalhe) {
                $contacorrente = $empenho->numero . str_pad(
                    $empenhodetalhe->naturezasubitem->codigo,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

                AtualizasaldosmpenhosJobs::dispatch(
                    $ug,
                    $amb,
                    $ano,
                    $contacorrente,
                    $mes,
                    $empenhodetalhe,
                    $contas_contabeis,
                    backpack_user()
                )->onQueue('atualizasaldone');

//                $this->teste($ug,
//                    $amb,
//                    $ano,
//                    $contacorrente,
//                    $mes,
//                    $empenhodetalhe,
//                    $contas_contabeis,
//                    backpack_user());
            }
        }

        if (backpack_user()) {
            Alert::success('Atualização de Empenhos em Andamento!')->flash();
            return redirect('/execfin/empenho');
        }
    }

    public function teste(
        $ug,
        $amb,
        $ano,
        $contacorrente,
        $mes,
        $empenhodetalhado,
        $contas_contabeis,
        $user
    ) {

        $dado = [];
        foreach ($contas_contabeis as $item => $valor) {
            $contacontabil1 = $valor;
            $saldoAtual = 0;

            $unidade = Unidade::where('codigo', $ug)
                ->first();
            $gestao = $unidade->gestao;

            $saldocontabilSta = new ConsultaApiSta();
            $retorno = null;
            $retorno = $saldocontabilSta->saldocontabilAnoUgGestaoContacontabilContacorrente(
                $ano,
                $ug,
                $gestao,
                $contacontabil1,
                $contacorrente
            );

            if ($retorno != null) {
                $saldoAtual = $retorno['saldo'];
                $dado[$item] = $saldoAtual;
            } else {
                $dado[$item] = $saldoAtual;
            }
        }

        $empenhodetalhado->fill($dado);
        $empenhodetalhado->push();
    }

    public function migracaoRp($ug_id)
    {
        $unidade = Unidade::find($ug_id);
        $rp_antigos = $this->atualizaEmpenhosRpsAntigos($ug_id);

        $ano = date('Y');

        $migracao_url = config('migracao.api_sta');
        $url = $migracao_url . '/api/rp/ug/' . $unidade->codigo;
        //        $dados = json_decode(file_get_contents($migracao_url . '/api/empenho/ano/' . $ano . '/ug/' . $unidade->codigo),
//            true);

        $dados = $this->buscaDadosUrl($url);

        foreach ($dados as $d) {
            $credor = $this->buscaFornecedor($d);

            if ($d['picodigo'] != "") {
                $pi = $this->buscaPi($d);
            }

            if (isset($pi->id)) {
                $pi_id = $pi->id;
            } else {
                $pi_id = null;
            }

            $naturezadespesa = Naturezadespesa::where('codigo', $d['naturezadespesa'])
                ->first();

            $empenho = Empenho::where('numero', '=', trim($d['numero']))
                ->where('unidade_id', '=', $unidade->id)
                ->withTrashed()
                ->first();

            if (!isset($empenho->id)) {
                $empenho = Empenho::create([
                    'numero' => trim($d['numero']),
                    'unidade_id' => $unidade->id,
                    'fornecedor_id' => $credor->id,
                    'planointerno_id' => $pi_id,
                    'naturezadespesa_id' => $naturezadespesa->id,
                    'fonte' => trim($d['fonte']),
                    'rp' => 1
                ]);
            } else {
                $empenho->fornecedor_id = $credor->id;
                $empenho->planointerno_id = $pi_id;
                $empenho->naturezadespesa_id = $naturezadespesa->id;
                $empenho->fonte = trim($d['fonte']);
                $empenho->deleted_at = null;
                $empenho->rp = 1;
                $empenho->save();
            }

            foreach ($d['itens'] as $item) {
                $naturezasubitem = Naturezasubitem::where('codigo', $item['subitem'])
                    ->where('naturezadespesa_id', $naturezadespesa->id)
                    ->first();

                $empenhodetalhado = Empenhodetalhado::where('empenho_id', '=', $empenho->id)
                    ->where('naturezasubitem_id', '=', $naturezasubitem->id)
                    ->first();

                if (!isset($empenhodetalhado)) {
                    $empenhodetalhado = Empenhodetalhado::create([
                        'empenho_id' => $empenho->id,
                        'naturezasubitem_id' => $naturezasubitem->id
                    ]);
                }
            }
        }
    }

    public function atualizaEmpenhosRpsAntigos($unidade_id)
    {
        $empenhos = Empenho::where('unidade_id', $unidade_id)
            ->update(['rp' => false]);

        return $empenhos;
    }

    public function migracaoEmpenho($ug_id, $ano_request = null)
    {
        $unidade = Unidade::find($ug_id);

        $ano = date('Y');

        if ($ano_request) {
            $ano = $ano_request;
        }

        $migracao_url = config('migracao.api_sta');
        $url = $migracao_url . '/api/empenho/ano/' . $ano . '/ug/' . $unidade->codigo;

//        $dados = (env('APP_ENV', 'production') === 'production')
//            ? $this->buscaDadosFileGetContents($url)
//            : $this->buscaDadosCurl($url);

        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 600,
                'ignore_errors' => true,
            ),
            "ssl" => array(
                "allow_self_signed" => true,
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        ));

        $dados = $this->buscaDadosFileGetContents($url, $context);

        $pkcount = is_array($dados) ? count($dados) : 0;
        if ($pkcount > 0) {
            foreach ($dados as $d) {
                $credor = $this->buscaFornecedor($d);

                if ($d['picodigo']) {
                    $pi = $this->buscaPi($d);
                }

                $naturezadespesa = $this->trataPiNdSubitem($d['naturezadespesa'], 'ND', null, $d['naturezadespesadescricao']);

                if ($naturezadespesa) {
                    $empenho = Empenho::updateOrCreate(
                        [
                            'numero' => trim($d['numero']),
                            'unidade_id' => $unidade->id
                        ],
                        [
                            'fornecedor_id' => $credor->id,
                            'planointerno_id' => $pi->id,
                            'naturezadespesa_id' => $naturezadespesa,
                            'fonte' => trim($d['fonte']),
                        ]
                    );

                    foreach ($d['itens'] as $item) {
                        $naturezasubitem = $this->trataPiNdSubitem($item['subitem'], 'SUBITEM', $naturezadespesa, $item['subitemdescricao']);

                        if ($naturezasubitem) {
                            $empenhodetalhado = Empenhodetalhado::updateOrCreate([
                                'empenho_id' => $empenho->id,
                                'naturezasubitem_id' => $naturezasubitem
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function buscaNd($codigo, $descricao)
    {
        $planointerno = Naturezadespesa::updateOrCreate(
            [
                'codigo' => $codigo
            ],
            [
                'descricao' => strtoupper($descricao),
                'situacao' => true
            ]
        );

        return $planointerno;
    }

    public function executaCargaEmpenhos()
    {
        $unidades = Unidade::whereHas('contratos', function ($c) {
            $c->where('situacao', true);
        })
            ->where('situacao', true)
            ->where('utiliza_siafi', true)
            ->get();

        $base = new AdminController();
        $ano_antigo = $base->retornaDataMaisOuMenosQtdTipoFormato('Y', '-', '10', 'years', date('Y-m-d'));
        $ano_corrente = date('Y');

        foreach ($unidades as $unidade) {
            for ($i = $ano_antigo; $i <= $ano_corrente; $i++) {
                MigracaoCargaEmpenhoJob::dispatch($unidade->id, $i)->onQueue('migracaoempenho');
            }
        }

        if (backpack_user()) {
            Alert::success('Migração de Empenhos em Andamento!')->flash();
            return redirect('/execfin/empenho');
        }
    }

    public function buscaFornecedor($credor)
    {
        $tipo = 'JURIDICA';
        if (strlen($credor['cpfcnpjugidgener']) == 14) {
            $tipo = 'FISICA';
        } elseif (strlen($credor['cpfcnpjugidgener']) == 9) {
            $tipo = 'IDGENERICO';
        } elseif (strlen($credor['cpfcnpjugidgener']) == 6) {
            $tipo = 'UG';
        }

        $fornecedor = Fornecedor::updateOrCreate(
            [
                'cpf_cnpj_idgener' => $credor['cpfcnpjugidgener']
            ],
            [
                'tipo_fornecedor' => $tipo,
                'nome' => strtoupper(trim($credor['nome']))
            ]
        );

        return $fornecedor;
    }

    public function buscaPi($pi)
    {
        $planointerno = Planointerno::updateOrCreate(
            [
                'codigo' => $pi['picodigo']
            ],
            [
                'descricao' => strtoupper($pi['pidescricao']),
                'situacao' => true
            ]
        );

        return $planointerno;
    }

    public function buscaDadosUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);

        curl_close($ch);

        return json_decode($data, true);
    }

    public function incluirEmpenhoSiafi()
    {
        $retorno = null;
        $empenhos = SfOrcEmpenhoDados::where('situacao', 'EM PROCESSAMENTO')
            ->get();

        if ($empenhos) {
            foreach ($empenhos as $empenho) {
                $user = BackpackUser::where('cpf', $empenho->cpf_user)
                    ->first();
                $ws_siafi = new Execsiafi;
                $ano = config('app.ano_minuta_empenho');
                $retorno = $ws_siafi->incluirNe($user, $empenho->ugemitente, env('AMBIENTE_SIAFI'), $ano, $empenho);
                $empenho->update($retorno);

                if ($retorno['situacao'] == 'EMITIDO') {
                    $empenho = $this->criaEmpenhoFromMinuta($empenho);

                    DevolveMinutaSiasg::create([
                        'minutaempenho_id' => $empenho->minutaempenho_id,
                        'situacao' => 'Pendente'
                    ]);
                }
            }
        }
    }

    public function criaEmpenhoFromMinuta(SfOrcEmpenhoDados $empenho)
    {
        $array_empenho1 = [
            'numero' => trim($empenho->mensagemretorno),
            'unidade_id' => $empenho->minuta_empenhos->saldo_contabil->unidade_id,
        ];

        $array_empenho2 = [
            'fornecedor_id' => $empenho->minuta_empenhos->fornecedor_empenho_id,
            'planointerno_id' => $this->trataPiNdSubitem(substr($empenho->minuta_empenhos->saldo_contabil->conta_corrente, 31, 11), 'PI'),
            'naturezadespesa_id' => $this->trataPiNdSubitem(substr($empenho->minuta_empenhos->saldo_contabil->conta_corrente, 17, 6), 'ND'),
            'fonte' => substr($empenho->minuta_empenhos->saldo_contabil->conta_corrente, 7, 10)
        ];

        $novo_empenho = Empenho::firstOrCreate(
            $array_empenho1,
            $array_empenho2
        );

        $itens = SfItemEmpenho::where('sforcempenhodado_id', $empenho->id)
            ->get();

        $array_empenhodetalhado = [];
        foreach ($itens as $item) {
            $array_empenhodetalhado = [
                'empenho_id' => $novo_empenho->id,
                'naturezasubitem_id' => $this->trataPiNdSubitem($item->codsubelemento, 'SUBITEM', $array_empenho2['naturezadespesa_id'])
            ];
            $novo_empenhodetalhado = Empenhodetalhado::firstOrCreate($array_empenhodetalhado);
        }
        return $novo_empenho;
    }

    public function trataPiNdSubitem($dado, $tipo, $fk = null, $descricao = null)
    {
        if ($dado == '') {
            return null;
        }

        if ($tipo == 'PI') {
            $pi = Planointerno::firstOrCreate(
                ['codigo' => trim($dado)],
                [
                    'descricao' => ($descricao) ? $descricao : 'ATUALIZAR PI',
                    'situacao' => true
                ]
            );

            return $pi->id;
        }
        if ($tipo == 'ND') {
            $nd = Naturezadespesa::firstOrCreate(
                ['codigo' => trim($dado)],
                [
                    'descricao' => ($descricao) ? $descricao : 'ATUALIZAR ND',
                    'situacao' => true
                ]
            );

            return $nd->id;
        }
        if ($tipo == 'SUBITEM') {
            $ndsubitem = Naturezasubitem::firstOrCreate(
                [
                    'naturezadespesa_id' => $fk,
                    'codigo' => trim($dado),
                ],
                [
                    'descricao' => ($descricao) ? $descricao : 'ATUALIZAR SUBITEM',
                    'situacao' => true
                ]
            );

            return $ndsubitem->id;
        }
    }

    public function enviaEmpenhoSiasg()
    {

        $minutas = MinutaEmpenho::join(
            'minutaempenhos_remessa',
            'minutaempenhos_remessa.minutaempenho_id',
            '=',
            'minutaempenhos.id'
        )
            ->join(
                'devolve_minuta_siasg',
                'devolve_minuta_siasg.minutaempenhos_remessa_id',
                '=',
                'minutaempenhos_remessa.id'
            )
            ->join('unidades', 'unidades.id', '=', 'minutaempenhos.unidade_id')
            ->join('compras', 'compras.id', '=', 'minutaempenhos.compra_id')
            ->join('codigoitens', 'codigoitens.id', '=', 'compras.modalidade_id')
            ->join('fornecedores', 'fornecedores.id', '=', 'minutaempenhos.fornecedor_empenho_id')
            ->join(
                'saldo_contabil',
                'saldo_contabil.id',
                '=',
                'minutaempenhos.saldo_contabil_id'
            )
            ->join(
                DB::raw('unidades as unidade_saldo_contabil'),
                'unidade_saldo_contabil.id',
                '=',
                'saldo_contabil.unidade_id'
            )
            ->join(
                DB::raw('codigoitens as tipo_compra'),
                'tipo_compra.id',
                '=',
                'compras.tipo_compra_id'
            )
            ->join(
                DB::raw('codigoitens as tipo_empenho'),
                'tipo_empenho.id',
                '=',
                'minutaempenhos.tipo_empenho_id'
            )
            ->join(
                DB::raw('codigoitens as tipo_empenhopor'),
                'tipo_empenhopor.id',
                '=',
                'minutaempenhos.tipo_empenhopor_id'
            )
            ->leftJoin(
                'contratos',
                'contratos.id',
                '=',
                'minutaempenhos.contrato_id'
            )
            ->leftJoin(
                DB::raw('unidades as unidade_contrato'),
                'unidade_contrato.id',
                '=',
                'contratos.unidadeorigem_id'
            )
            ->where('devolve_minuta_siasg.situacao', 'Pendente')
            ->select(
                'minutaempenhos.id',
                DB::raw('left(minutaempenhos.mensagem_siafi,4) as anoEmpenho'),
                'data_emissao',
                'unidades.codigo',
                'codigoitens.descres as modalidade',
                'compras.numero_ano as num_ano',
                'fornecedores.cpf_cnpj_idgener',
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,8,10) AS "fonte"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,18,6) AS "nd"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,2,6) AS "ptrs"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,32,11) AS "plano_interno"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,24,8) AS "ugr"'),
                DB::raw('unidade_saldo_contabil.codigo AS "ugEmitente"'),
                'tipo_compra.descres as tp_compra',
                'tipo_empenho.descres as tipo_empenho',
                'minutaempenhos.unidade_id',
                'minutaempenhos.mensagem_siafi',
                'minutaempenhos.valor_total',
                'minutaempenhos.tipo_empenhopor_id',
                'minutaempenhos_remessa.id as minutaempenhos_remessa_id',
                DB::raw('devolve_minuta_siasg.id as devolve_id'),
                DB::raw("CASE
                                   WHEN tipo_empenhopor.descricao = 'Compra' THEN unidades.codigo
                                   WHEN tipo_empenhopor.descricao = 'Contrato' THEN unidade_contrato.codigo
                                   END AS \"uasgUsuario\""),
                DB::raw("
                    CASE
           WHEN tipo_empenhopor.descricao = 'Compra' THEN ''
           WHEN tipo_empenhopor.descricao = 'Contrato' THEN
                   unidade_contrato.codigo || '50' || REPLACE(numero, '/', '') ||
                   CASE
                       WHEN contratos.unidade_id <> contratos.unidadeorigem_id
                           THEN unidades.codigo
                       ELSE '000000'
                       END
           END                                                                          AS \"chaveContratoContinuado\"
                ")
            )
//            ->where('minutaempenhos.tipo_empenhopor_id', 256)//todo tirar esta linha
//            ->where('minutaempenhos.id', 154579)//todo tirar esta linha compra
//            ->where('minutaempenhos.id', 30208)//todo tirar esta linha contrato
//            ->where('minutaempenhos.id', 167750)//todo tirar esta linha contrato
//            ->where('minutaempenhos.id', 171620)//todo tirar esta linha compra sispp
//            ->where('minutaempenhos.id', 42751)//todo tirar esta linha compra sispp QTD QUEBRADA
//            ->where('minutaempenhos.id', 117992)//todo tirar esta linha compra sispp operacao invalido
//            ->where('minutaempenhos.id', 171620)//todo tirar esta linha compra sispp subitem invalido

            //->where('minutaempenhos.unidade_id', 1)//todo tirar esta linha
//                ->whereNull('mensagem_siasg')
            ->where('devolve_minuta_siasg.situacao', 'Pendente')
            //->where('devolve_minuta_siasg.id', 13714)//todo retirar esta linha
            ->orderBy('devolve_minuta_siasg.created_at', 'asc')
//            ->take(2)
            ->get();


        foreach ($minutas as $index => $minuta) {
            $itens = $minuta->getItens($minuta->minutaempenhos_remessa_id)->toArray();
            $tipoUASG = ($itens[0]['tipoUASG'] ?? 'G');

            $valorTotalEmpenho = ($itens[0]['valorTotalEmpenho'] < 0)
                ? $itens[0]['valorTotalEmpenho'] * -1
                : $itens[0]['valorTotalEmpenho'];

            $itens = array_map(
                function ($itens) {
                    unset($itens['tipoUASG'], $itens['valorTotalEmpenho']);

                    // SOMENTE QUANTIDADE POSITIVA
                    $itens['quantidadeEmpenhada'] = ($itens['quantidadeEmpenhada'] < 0)
                        ? $itens['quantidadeEmpenhada'] * -1
                        : $itens['quantidadeEmpenhada'];
                    return $itens;
                },
                $itens
            );

            $array = [
                "anoEmpenho" => $minuta->anoempenho,
                "chaveCompra" => $minuta->codigo . $minuta->modalidade . str_replace('/', '', $minuta->num_ano),
                "chaveContratoContinuado" => $minuta->chaveContratoContinuado,//ugorigem/ugid
                "dataEmissao" => str_replace('-', '', $minuta->data_emissao),
                "favorecido" => $this->retornaSomenteNumeros($minuta->cpf_cnpj_idgener),  //ver detalhe do estrangeiro
                "fonte" => $minuta->fonte,
                "itemEmpenho" => $itens,
                "nd" => $minuta->nd,
                "numeroEmpenho" => $minuta->mensagem_siafi,
                "planoInterno" => $minuta->plano_interno,
                "ptres" => $minuta->ptrs,
                "tipoCompra" => (string)(int)$minuta->tp_compra,
                "tipoEmpenho" => $minuta->tipo_empenho,
                "tipoUASG" => $tipoUASG,
                "uasgUsuario" => $minuta->uasgUsuario,//contrato -ug origem ////// compra uasg minuta
                "ugEmitente" => $minuta->ugEmitente, //ug do saldo contabil
                "ugr" => trim($minuta->ugr),
                "valorTotalEmpenho" => (string)$valorTotalEmpenho
            ];
            EnviaEmpenhoSiasgJob::dispatch($array, $minuta->devolve_id)->onQueue('enviarempenhosiasg');

        }
    }

    public function montaContextJsonGetPeerFalse()
    {
        $context_options = array(
//            'https' => array(
//                'method' => 'GET',
//                'header' => "Content-type: application/json"
//            ),
            'ssl' => array(
                'verify_peer' => false,
                "verify_peer_name" => false,
            )
        );

        return stream_context_create($context_options);
    }

    public function array_slice_assoc($array, $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }

    public function array_pop_n(array $arr, $n)
    {
        array_splice($arr, 0, -$n);
        return $arr;
    }

//    public function executaAlteracaoEmpenho()
//    {
//        $id = 94;
//        $sforcempenhodados = SfOrcEmpenhoDados::find($id);
//
//        $user = BackpackUser::where('cpf', $sforcempenhodados->cpf_user)
//            ->first();
//        $ws_siafi = new Execsiafi;
//        $ano = config('app.ano_minuta_empenho');
//
//        $retorno = $ws_siafi->alterarNe($user, $sforcempenhodados->ugemitente, config('app.ambiente_siafi'), $ano, $sforcempenhodados);
//
//        $sforcempenhodados->update($retorno);
//    }
}
