<?php

namespace App\Http\Controllers\Execfin;

use App\Jobs\MigracaoempenhoJob;
use App\Models\Empenho;
use App\Models\Fornecedor;
use App\Models\Naturezadespesa;
use App\Models\Naturezasubitem;
use App\Models\Planointerno;
use App\Models\Unidade;
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

        (backpack_user()->can('empenho_inserir')) ? $this->crud->addButtonFromView('top', 'migrarempenho', 'migrarempenho', 'end') : null;
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

        $fornecedores = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'id')
            ->orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();

        $planointerno = Planointerno::select(DB::raw("CONCAT(codigo,' - ',descricao) AS nome"), 'id')
            ->orderBy('codigo', 'asc')->pluck('nome', 'id')->toArray();

        $naturezadespesa = Naturezadespesa::select(DB::raw("CONCAT(codigo,' - ',descricao) AS nome"), 'id')
            ->orderBy('codigo', 'asc')->pluck('nome', 'id')->toArray();


        $campos = $this->Campos($unidade, $fornecedores, $planointerno, $naturezadespesa);

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

    public function Campos($unidade, $fornecedores, $planointerno, $naturezadespesa)
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
                'name' => 'fornecedor_id',
                'label' => "Credor / Fornecedor",
                'type' => 'select2_from_array',
                'options' => $fornecedores,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'planointerno_id',
                'label' => "Plano Interno (PI)",
                'type' => 'select2_from_array',
                'options' => $planointerno,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'naturezadespesa_id',
                'label' => "Natureza Despesa (ND)",
                'type' => 'select2_from_array',
                'options' => $naturezadespesa,
                'allows_null' => true,
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],

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

        return $content;
    }

    public function migracaoEmpenho()
    {

        $unidades = Unidade::where('tipo', 'E')
            ->get();

        $ano = '2019';

        foreach ($unidades as $unidade) {
            $migracao_url = config('migracao.api_sta');
            $dados = json_decode(file_get_contents($migracao_url . '/api/empenho/ano/'.$ano.'/ug/'.$unidade->codigo), true);

            foreach ($dados as $d) {

                dd($d);

                $credor = $this->buscaFornecedor($d['credor']);

                if ($d['pi']['codigo']) {
                    $pi = $this->buscaPi($d['pi']);
                }

                $naturezasubitem = Naturezasubitem::whereHas('naturezadespesa', function ($query) use ($d) {
                    $query->where('codigo', '=', $d['naturezadespesa']);
                })
                    ->where('codigo', '=', str_pad($d['subitem'], 2, "0", STR_PAD_LEFT))
                    ->first();

                $empenho = Empenho::where('numero', '=', $d['numero'])
                    ->where('unidade_id', '=', $unidade->id)
                    ->where('fornecedor_id', '=', $credor->id)
                    ->where('planointerno_id', '=', $pi->id)
                    ->where('naturezadespesa_id', '=', $naturezasubitem->naturezadespesa_id)
                    ->first();

                if (!$empenho) {
                    $empenho = Empenho::create([
                        'numero' => $d['numero'],
                        'unidade_id' => $unidade->id,
                        'fornecedor_id' => $credor->id,
                        'planointerno_id' => $pi->id,
                        'naturezadespesa_id' => $naturezasubitem->naturezadespesa_id
                    ]);
                }

                $empenhodetalhado = Empenhodetalhado::where('empenho_id', '=', $empenho->id)
                    ->where('naturezasubitem_id', '=', $naturezasubitem->id)
                    ->first();

                if (!$empenhodetalhado) {
                    $empenhodetalhado = Empenhodetalhado::create([
                        'empenho_id' => $empenho->id,
                        'naturezasubitem_id' => $naturezasubitem->id
                    ]);
                }
            }


        }



//        MigracaoempenhoJob::dispatch();
//
//        \Alert::success('Migração de Empenhos em Andamento!')->flash();

//        return redirect('/execfin/empenho');

        return 'Concluido';
    }




}
