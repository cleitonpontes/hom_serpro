<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Fornecedor;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use FormBuilder;
use App\Forms\InserirItemContratoMinutaForm;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AditivoRequest as StoreRequest;
use App\Http\Requests\AditivoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
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
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'contratohistorico.*');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        $this->crud->addClause('where', 'tipo_id', '=', $tps->id);
        $this->crud->orderBy('data_assinatura', 'asc');

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->addButtonFromView('line', 'morecontratohistorico', 'morecontratohistorico', 'end');
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

        $fornecedores = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'id')
            ->orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '=', 'Termo Aditivo')
//            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();


        $campos = $this->Campos($fornecedores, $tipos, $contrato_id, $unidade);
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
                'label' => 'Observação',
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
                'label' => 'Número Aditivo',
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
                'label' => 'Informações Complementares',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'vigencia_inicio',
                'label' => 'Vig. Início',
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
                'label' => 'Núm. Parcelas',
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
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'getRetroativoMesAnoReferenciaDe',
                'label' => 'Retroativo Mês Ref. De', // Table column heading
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
                'label' => 'Retroativo Mês Ref. Até', // Table column heading
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

    public function Campos($fornecedores, $tipos, $contrato_id, $unidade)
    {
        $contrato = Contrato::find($contrato_id);

        $campos = [
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
            [
                // select_from_array
                'name' => 'tipo_id',
                'label' => "Tipo",
                'type' => 'select_from_array',
                'options' => $tipos,
                'allows_null' => false,
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'numero',
                'label' => 'Número Termo Aditivo',
                'type' => 'numcontrato',
                'tab' => 'Dados Gerais',

            ],
            [
                'name' => 'observacao',
                'label' => 'Observação',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Gerais',
            ],
            [ // select_from_array
                'name' => 'unidade_id',
                'label' => "Unidade Gestora",
                'type' => 'select_from_array',
                'options' => $unidade,
                'allows_null' => false,
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
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
                'process_results_template' => 'gescon.process_results_fornecedor',
                'model' => "App\Models\Fornecedor", // foreign key model
                'data_source' => url("api/fornecedor"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione o fornecedor", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
                'tab' => 'Dados Aditivo',
            ],
//            [ // select_from_array
//                'name' => 'fornecedor_id',
//                'label' => "Fornecedor",
//                'type' => 'select2_from_array',
//                'options' => $fornecedores,
//                'allows_null' => true,
//                'default' => $contrato->fornecedor_id,
//                'tab' => 'Dados Aditivo',
////                'default' => 'one',
//                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
//            ],
            [   // Date
                'name' => 'data_assinatura',
                'label' => 'Data Assinatura Aditivo',
                'type' => 'date',
                'tab' => 'Dados Aditivo',
            ],
            [   // Date
                'name' => 'data_publicacao',
                'label' => 'Data Publicação Aditivo',
                'type' => 'date',
                'tab' => 'Dados Aditivo',
            ],
            [
                'name' => 'info_complementar',
                'label' => 'Informações Complementares',
                'type' => 'textarea',
                'default' => $contrato->info_complementar,
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Aditivo',
            ],
            [
                'name' => 'itens',
                'type' => 'itens_contrato_aditivo_list',
                'label' => 'Teste',
                'tab' => 'Itens do contrato',
                'form' => $this->retonaFormModal()
            ],
            [   // Date
                'name' => 'vigencia_inicio',
                'label' => 'Data Vig. Início',
                'type' => 'date',
                'default' => $contrato->vigencia_inicio,
                'tab' => 'Vigência / Valores',
            ],
            [   // Date
                'name' => 'vigencia_fim',
                'label' => 'Data Vig. Fim',
                'type' => 'date',
                'default' => $contrato->vigencia_fim,
                'tab' => 'Vigência / Valores',
            ],
            [   // Number
                'name' => 'valor_global',
                'label' => 'Valor Global',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'valor_global',
                ], // allow decimals
                'prefix' => "R$",
                'default' => number_format($contrato->valor_global, 2, ',', '.'),
                'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],
            [   // Number
                'name' => 'num_parcelas',
                'label' => 'Núm. Percelas',
                'type' => 'number',
                // optionals
                'attributes' => [
                    "step" => "any",
                    "min" => '1',
                ], // allow decimals
                'default' => $contrato->num_parcelas,
//                'prefix' => "R$",
                'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],
            [   // Number
                'name' => 'valor_parcela',
                'label' => 'Valor Parcela',
                'type' => 'money',
                // optionals
                'attributes' => [
                    'id' => 'valor_parcela',
                ], // allow decimals
                'prefix' => "R$",
                'default' => number_format($contrato->valor_parcela, 2, ',', '.'),
                'tab' => 'Vigência / Valores',
                // 'suffix' => ".00",
            ],


            [ // select_from_array
                'name' => 'retroativo',
                'label' => "Retroativo?",
                'type' => 'radio',
                'options' => [0 => 'Não', 1 => 'Sim'],
                'default'    => 0,
                'inline'      => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_mesref_de',
                'label' => "Mês Referência De",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_anoref_de',
                'label' => "Ano Referência De",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
//                'default'    => date('Y'),
                'allows_null' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_mesref_ate',
                'label' => "Mês Referência Até",
                'type' => 'select2_from_array',
                'options' => config('app.meses_referencia_fatura'),
                'allows_null' => true,
                'tab' => 'Retroativo',
            ],
            [ // select_from_array
                'name' => 'retroativo_anoref_ate',
                'label' => "Ano Referência Até",
                'type' => 'select2_from_array',
                'options' => config('app.anos_referencia_fatura'),
//                'default'    => date('Y'),
                'allows_null' => true,
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
                'default'    => 1,
                'inline'      => true,
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
        $valor_parcela = str_replace(',', '.', str_replace('.', '', $request->input('valor_parcela')));
        $request->request->set('valor_parcela', number_format(floatval($valor_parcela), 2, '.', ''));

        $valor_global = str_replace(',', '.', str_replace('.', '', $request->input('valor_global')));
        $request->request->set('valor_global', number_format(floatval($valor_global), 2, '.', ''));
        $request->request->set('valor_inicial', number_format(floatval($valor_global), 2, '.', ''));

        $soma_subtrai = $request->input('retroativo_soma_subtrai');

        $retroativo_valor = str_replace(',', '.', str_replace('.', '', $request->input('retroativo_valor')));

        if($soma_subtrai == '0'){
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '') * -1;
        }else{
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '');
        }

        $request->request->set('retroativo_valor', $retroativo_valor);


        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $valor_parcela = str_replace(',', '.', str_replace('.', '', $request->input('valor_parcela')));
        $request->request->set('valor_parcela', number_format(floatval($valor_parcela), 2, '.', ''));

        $valor_global = str_replace(',', '.', str_replace('.', '', $request->input('valor_global')));
        $request->request->set('valor_global', number_format(floatval($valor_global), 2, '.', ''));

        $soma_subtrai = $request->input('retroativo_soma_subtrai');

        $retroativo_valor = str_replace(',', '.', str_replace('.', '', $request->input('retroativo_valor')));

        if($soma_subtrai == '0'){
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '') * -1;
        }else{
            $retroativo_valor = number_format(floatval($retroativo_valor), 2, '.', '');
        }

        $request->request->set('retroativo_valor', $retroativo_valor);

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

    public function retonaFormModal()
    {
        return FormBuilder::create(InserirItemContratoMinutaForm::class);
    }
}
