<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Catmatseritem;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\Contratoitem;
use App\Models\Fornecedor;
use App\Models\Saldohistoricoitem;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use FormBuilder;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AditivoRequest as StoreRequest;
use App\Http\Requests\AditivoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class AditivoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AditivoCrudController extends CrudController
{
    public function setup()
    {
        $contrato_id = \Route::current()->parameter('contrato_id');
        $aditivo_id = \Route::current()->parameter('aditivo');

        $contrato = Contrato::where('id', '=', $contrato_id)
            ->where('unidade_id', '=', session()->get('user_ug_id'))->first();
        if (!$contrato) {
            abort('403', config('app.erro_permissao'));
        }

        $tps = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '=', 'Termo Aditivo')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratohistorico');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/' . $contrato_id . '/aditivos');
        $this->crud->setEntityNameStrings('Termo Aditivo', 'Termos Aditivos');

        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->setEditContentClass('col-md-12');


        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'contratohistorico.*');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addClause('where', 'tipo_id', '=', $tps->id);
        $this->crud->orderBy('data_assinatura', 'asc');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
//        $this->crud->addButtonFromView('line', 'morecontratohistorico', 'morecontratohistorico', 'end');
        $this->crud->enableExportButtons();
//        $this->crud->disableResponsiveTable();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contratoaditivo_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contratoaditivo_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('contratoaditivo_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $campos = $this->Campos($aditivo_id, $contrato_id, $unidade);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in AditivoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getReceitaDespesaHistorico',
                'label' => 'Receita / Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getReceitaDespesaHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'observacao',
                'label' => 'Observa????o',
                'type' => 'text',
//                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numero',
                'label' => 'N??mero Aditivo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getFornecedorHistorico',
                'label' => 'Fornecedor', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getFornecedorHistorico', // the method in your Model
                'orderable' => true,
                'limit' => 1000,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
//                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
//                    $query->orWhere('fornecedores.nome', 'like', "%$searchTerm%");
//                },
            ],
            [
                'name' => 'info_complementar',
                'label' => 'Informa????es Complementares',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_inicio',
                'label' => 'Vig. In??cio',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_fim',
                'label' => 'Vig. Fim',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrGlobalHistorico',
                'label' => 'Valor Global', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrGlobalHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'num_parcelas',
                'label' => 'N??m. Parcelas',
                'type' => 'number',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrParcelaHistorico',
                'label' => 'Valor Parcela', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrParcelaHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],

            [
                'name' => 'retroativo',
                'label' => 'Retroativo',
                'type' => 'boolean',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
                // optionally override the Yes/No texts
                'options' => [0 => 'N??o', 1 => 'Sim']
            ],
            [
                'name' => 'getRetroativoMesAnoReferenciaDe',
                'label' => 'Retroativo M??s Ref. De', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getRetroativoMesAnoReferenciaDe', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getRetroativoMesAnoReferenciaAte',
                'label' => 'Retroativo M??s Ref. At??', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getRetroativoMesAnoReferenciaAte', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'retroativo_vencimento',
                'label' => 'Vencimento Retroativo',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrRetroativoValor',
                'label' => 'Valor Retroativo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrRetroativoValor', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
        ];

        return $colunas;

    }

    public function Campos($aditivo_id, $contrato_id, $unidade)
    {
        $contrato = Contrato::find($contrato_id);

        $options = Codigoitem::select('codigoitens.descricao')
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao', '=', 'Tipo Qualificacao Contrato')->get()->toArray();

        $options = json_encode($options);

        $campos = [

            [   // Hidden
                'name' => 'aditivo_id',
                'type' => 'hidden',
                'default' => $aditivo_id,
            ],
            [   // Hidden
                'name' => 'receita_despesa',
                'type' => 'hidden',
                'default' => $contrato->receita_despesa,
            ],
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato->id,
            ],
            [   // Hidden
                'name' => 'tipo_contrato',
                'type' => 'hidden',
                'default' => $contrato->tipo->descricao,
                'attributes' => [
                    'id' => 'tipo_contrato'
                ]
            ],
            [   // Hidden
                'name' => 'tipo_id',
                'type' => 'hidden',
                'default' => $contrato->tipo_id,
            ],
            [   // Hidden
                'name' => 'options_qualificacao',
                'type' => 'hidden',
                'default' => $options,
            ],

            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => 'Qualifica????o',
                'name' => 'qualificacoes',
                'type' => 'select2_from_ajax_multiple_qualificacao',
                'entity' => 'qualificacoes',
                'placeholder' => 'Selecione as qualifica????es',
                'minimum_input_length' => 0,
                'data_source' => url('api/qualificacao'),
                'model' => 'App\Models\Codigoitem',
                'attribute' => 'descricao',
                'pivot' => true,
                'tab' => 'Dados Gerais',
            ],
            [
                'name' => 'numero',
                'label' => 'N??mero Termo Aditivo',
                'type' => 'numcontrato',
                'tab' => 'Dados Gerais',

            ],
            [
                'name' => 'observacao',
                'label' => 'Objeto do TA',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)",
                ],
//                'default' => $contrato->objeto,
                'tab' => 'Dados Gerais',
            ],
            [ // select_from_array
                'name' => 'unidade_id',
                'label' => "Unidade Gestora",
                'type' => 'select_from_array',
                'options' => $unidade,
                'allows_null' => false,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                // 1-n relationship
                'label' => "Fornecedor", // Table column heading
                'type' => "select2_from_ajax",
                'name' => 'fornecedor_id', // the column that contains the ID of that connected entity
                'entity' => 'fornecedor', // the method that defines the relationship in your Model
                'attribute' => "cpf_cnpj_idgener", // foreign key attribute that is shown to user
                'attribute2' => "nome", // foreign key attribute that is shown to user
                'attributes' => [
                    'disabled' => 'disabled'
                ],
                'default' => $contrato->fornecedor_id,
                'process_results_template' => 'gescon.process_results_fornecedor',
                'model' => "App\Models\Fornecedor", // foreign key model
                'data_source' => url("api/fornecedor"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione o fornecedor", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
                'tab' => 'Dados Aditivo',
            ],
            [   // Date
                'name' => 'data_assinatura',
                'label' => 'Data Assinatura Aditivo',
                'type' => 'date',
                'tab' => 'Dados Aditivo',
            ],
            [   // Date
                'name' => 'data_publicacao',
                'label' => 'Data Publica????o Aditivo',
                'type' => 'date',
                'tab' => 'Dados Aditivo',
            ],
            [
                'name' => 'info_complementar',
                'label' => 'Informa????es Complementares',
                'type' => 'textarea',
                'default' => $contrato->info_complementar,
                'attributes' => [
                    'onkeyup' => "maiuscula(this)",
                ],
                'tab' => 'Dados Aditivo',
            ],
            [
                'name' => 'itens',
                'type' => 'itens_contrato_aditivo_list',
                'tab' => 'Itens do contrato',
            ],
            [   // Hidden
                'name' => 'descricao_tipo_contrato',
                'type' => 'hidden',
                'default' => $contrato->tipo->descricao,
                'tab' => 'Itens do contrato'
            ],
            [
                'label' => "adicionaCampoRecuperaGridItens",
                'type' => "hidden",
                'name' => 'adicionaCampoRecuperaGridItens',
                'default' => "{{old('name')}}",
                'tab' => 'Itens do contrato'
            ],
            [   // Date
                'name' => 'vigencia_inicio',
                'label' => 'Data Vig. In??cio',
                'type' => 'date',
                'default' => $contrato->vigencia_inicio,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'tab' => 'Vig??ncia / Valores',
            ],
            [   // Date
                'name' => 'vigencia_fim',
                'label' => 'Data Vig. Fim',
                'type' => 'date',
                'default' => $contrato->vigencia_fim,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'tab' => 'Vig??ncia / Valores',
            ],
            [   // Number
                'name' => 'valor_global',
                'label' => 'Valor Global',
                'type' => 'number',
                // optionals
                'attributes' => [
                    'id' => 'valor_global',
                    'readonly' => 'readonly',
                    'step' => '0.0001',
                ], // allow decimals
                'prefix' => "R$",
                'default' => number_format($contrato->valor_global, 2, ',', '.'),
                'tab' => 'Vig??ncia / Valores',
                // 'suffix' => ".00",
            ],
            [   // Number
                'name' => 'num_parcelas',
                'label' => 'N??m. Parcelas',
                'type' => 'number',
                // optionals
                'attributes' => [
                    "step" => "any",
                    "min" => '1',
                    'readonly' => 'readonly',
                ], // allow decimals
                'default' => $contrato->num_parcelas,
//                'prefix' => "R$",
                'tab' => 'Vig??ncia / Valores',
                // 'suffix' => ".00",
            ],
            [   // Number
                'name' => 'valor_parcela',
                'label' => 'Valor Parcela',
                'type' => 'number',
                // optionals
                'attributes' => [
                    'id' => 'valor_parcela',
                    'readonly' => 'readonly',
                    'step' => '0.0001',
                ], // allow decimals
                'prefix' => "R$",
                'default' => number_format($contrato->valor_parcela, 2, ',', '.'),
                'tab' => 'Vig??ncia / Valores',
                // 'suffix' => ".00",
            ],


            [ // select_from_array
                'name' => 'retroativo',
                'label' => "Retroativo?",
                'type' => 'radio',
                'options' => [0 => 'N??o', 1 => 'Sim'],
                'default' => 0,
                'attributes' => [
                    'disabled' => 'disabled',
                ],
                'inline' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_mesref_de',
                'label' => "M??s Refer??ncia De",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => true,
                'attributes' => [
                    'id' => 'retroativo_mesref_de',
                    'disabled' => 'disabled'
                ],
                'default' => 04,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_anoref_de',
                'label' => "Ano Refer??ncia De",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
//                'default'    => date('Y'),
                'allows_null' => true,
                'attributes' => [
                    'disabled' => 'disabled'
                ],
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_mesref_ate',
                'label' => "M??s Refer??ncia At??",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => true,
                'attributes' => [
                    'disabled' => 'disabled'
                ],
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_anoref_ate',
                'label' => "Ano Refer??ncia At??",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
//                'default'    => date('Y'),
                'allows_null' => true,
                'attributes' => [
                    'disabled' => 'disabled'
                ],
                'tab' => 'Retroativo',
            ],
            [   // Date
                'name' => 'retroativo_vencimento',
                'label' => 'Vencimento Retroativo',
                'type' => 'date',
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_soma_subtrai',
                'label' => "Soma ou Subtrai?",
                'type' => 'radio',
                'options' => [1 => 'Soma', 0 => 'Subtrai'],
                'default' => 1,
                'attributes' => [
                    'disabled' => 'disabled',
                ],
                'inline' => true,
                'tab' => 'Retroativo',
            ],
            [   // Number
                'name' => 'retroativo_valor',
                'label' => 'Valor Retroativo',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'retroativo_valor',
                ], // allow decimals
                'prefix' => "R$",
                'tab' => 'Retroativo',
                // 'suffix' => ".00",
            ],

        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {
        $valor_parcela = $request->input('valor_parcela');
        $request->request->set('valor_parcela', $valor_parcela);

        $valor_global = $request->input('valor_global');
        $request->request->set('valor_global', $valor_global);
        $request->request->set('valor_inicial', $valor_global);

        $soma_subtrai = $request->input('retroativo_soma_subtrai');

        $retroativo_valor = str_replace(',', '.', str_replace('.', '', $request->input('retroativo_valor')));

        if ($soma_subtrai == '0') {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '') * -1;
        } else {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '');
        }

        $tipo_id = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo de Contrato');
        })
            ->where('descricao', 'Termo Aditivo')
            ->first();

        $request->request->set('tipo_id', $tipo_id->id);

        $request->request->set('retroativo_valor', $retroativo_valor);

        DB::beginTransaction();
        try {
            // your additional operations before save here
            $redirect_location = parent::storeCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry

            // altera os itens do contrato
            if (!empty($request->get('qtd_item'))) {
                $this->criarSaldoHistoricoItens($request->all(), $this->crud->entry->id);
            }
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
            dd($exc);
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        return redirect()->route('crud.publicacao.index',['contrato_id'=>$request->input('contrato_id')]);
    }

    public function update(UpdateRequest $request)
    {
        $valor_parcela = $request->input('valor_parcela');
        $request->request->set('valor_parcela', $valor_parcela);

        $valor_global = $request->input('valor_global');
        $request->request->set('valor_global', $valor_global);

        $soma_subtrai = $request->input('retroativo_soma_subtrai');

        $retroativo_valor = str_replace(',', '.', str_replace('.', '', $request->input('retroativo_valor')));

        if ($soma_subtrai == '0') {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '') * -1;
        } else {
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '');
        }

        $request->request->set('retroativo_valor', $retroativo_valor);
        $tipo_id = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo de Contrato');
        })
            ->where('descricao', 'Termo Aditivo')
            ->first();

        $request->request->set('tipo_id', $tipo_id->id);
        DB::beginTransaction();
        try {
            // your additional operations before save here
            $redirect_location = parent::updateCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry

            // altera os itens do contrato
            if (!empty($request->get('qtd_item'))) {
                $this->alterarItensContrato($request);
            }
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
            dd($exc);
        }
        return redirect()->route('crud.publicacao.index',['contrato_id'=>$request->input('contrato_id')]);
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'retroativo_mesref_de',
            'retroativo_anoref_de',
            'retroativo_mesref_ate',
            'retroativo_anoref_ate',
            'retroativo_valor',
            'fornecedor_id',
            'tipo_id',
            'categoria_id',
            'unidade_id',
            'fundamento_legal',
            'modalidade_id',
            'licitacao_numero',
            'data_assinatura',
            'data_publicacao',
            'valor_inicial',
            'valor_global',
            'valor_parcela',
            'valor_acumulado',
            'situacao_siasg',
            'contrato_id',
            'receita_despesa',
            'processo',
            'objeto',
            'novo_valor_global',
            'novo_valor_parcela',
            'novo_num_parcelas',
            'data_inicio_novo_valor',
        ]);


        return $content;
    }

    private function alterarItensContrato(UpdateRequest $request)
    {
        $request = $request->all();

        $codigoitem = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo Saldo Itens');
        })
            ->where('descricao', 'Saldo Inicial Contrato Historico')
            ->first();

        foreach ($request['qtd_item'] as $key => $qtd) {

            if ($request['aditivo_item_id'][$key] !== 'undefined') {
                $saldoHistoricoIten = Saldohistoricoitem::find($request['aditivo_item_id'][$key]);
                $saldoHistoricoIten->quantidade = (double)$qtd;
                $saldoHistoricoIten->valorunitario = $request['vl_unit'][$key];
                $saldoHistoricoIten->valortotal = $request['vl_total'][$key];
                $saldoHistoricoIten->data_inicio = $request['data_inicio'][$key];
                $saldoHistoricoIten->periodicidade = $request['periodicidade'][$key];
                $saldoHistoricoIten->save();
            } else {
                $this->novoSaldoHistoricoItens($request, $key, $codigoitem);
            }
        }
    }

    private function novoSaldoHistoricoItens($request, $key, $codigoitem)
    {
        // caso seja um item vindo de um contrato o id do item aditivo ?? referente ao item do contrato.
        if ($request['contratoitem_id'][$key] !== 'undefined') {
            $request['aditivo_item_id'][$key] = $request['contratoitem_id'][$key];
        }

        $novoSaldoHistoricoIten = new Saldohistoricoitem();
        $novoSaldoHistoricoIten->saldoable_type = 'App\Models\Contratohistorico';
        $novoSaldoHistoricoIten->saldoable_id = $request['id'];
        $novoSaldoHistoricoIten->contratoitem_id = $request['aditivo_item_id'][$key];
        $novoSaldoHistoricoIten->tiposaldo_id = $codigoitem->id;
        $novoSaldoHistoricoIten->quantidade = (double)$request['qtd_item'][$key];
        $novoSaldoHistoricoIten->valorunitario = $request['vl_unit'][$key];
        $novoSaldoHistoricoIten->valortotal = $request['vl_total'][$key];
        $novoSaldoHistoricoIten->periodicidade = $request['periodicidade'][$key];
        $novoSaldoHistoricoIten->data_inicio = $request['data_inicio'][$key];
        $novoSaldoHistoricoIten->numero_item_compra = $request['numero_item_compra'][$key];
        $novoSaldoHistoricoIten->save();
    }

    private function criarSaldoHistoricoItens($request, $idContratoHistorico)
    {
        $codigoitem = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', 'Tipo Saldo Itens');
        })
            ->where('descricao', 'Saldo Inicial Contrato Historico')
            ->first();

        foreach ($request['qtd_item'] as $key => $qtd) {

            // caso seja um item vindo de um contrato o id do item aditivo ?? referente ao item do contrato.
            if ($request['contratoitem_id'][$key] !== 'undefined') {
                $request['aditivo_item_id'][$key] = $request['contratoitem_id'][$key];
            }

            $saldoHistoricoIten = new Saldohistoricoitem();
            $saldoHistoricoIten->saldoable_type = 'App\Models\Contratohistorico';
            $saldoHistoricoIten->saldoable_id = $idContratoHistorico;
            $saldoHistoricoIten->contratoitem_id = $request['aditivo_item_id'][$key];
            $saldoHistoricoIten->tiposaldo_id = $codigoitem->id;
            $saldoHistoricoIten->quantidade = (double)$qtd;
            $saldoHistoricoIten->valorunitario = $request['vl_unit'][$key];
            $saldoHistoricoIten->valortotal = $request['vl_total'][$key];
            $saldoHistoricoIten->periodicidade = $request['periodicidade'][$key];
            $saldoHistoricoIten->data_inicio = $request['data_inicio'][$key];
            $saldoHistoricoIten->numero_item_compra = $request['numero_item_compra'][$key];
            $saldoHistoricoIten->save();
        }
    }
}
