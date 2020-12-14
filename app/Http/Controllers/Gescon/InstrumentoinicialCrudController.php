<?php

namespace App\Http\Controllers\Gescon;

use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Fornecedor;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\InstrumentoinicialRequest as StoreRequest;
use App\Http\Requests\InstrumentoinicialRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class InstrumentoinicialCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InstrumentoinicialCrudController extends CrudController
{
    public function setup()
    {

        $contrato_id = \Route::current()->parameter('contrato_id');

        $contrato = Contrato::where('id','=',$contrato_id)
            ->where('unidade_id','=',session()->get('user_ug_id'))->first();
        if(!$contrato){
            abort('403', config('app.erro_permissao'));
        }

        $tps = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '=', 'Termo Aditivo')
            ->orWhere('descricao', '=', 'Termo de Apostilamento')
            ->orWhere('descricao', '=', 'Termo de Rescisão')
            ->pluck('id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contratohistorico');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato/'.$contrato_id.'/instrumentoinicial');
        $this->crud->setEntityNameStrings('Instrumento Inicial', 'Instrumento Inicial');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratohistorico.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratohistorico.unidade_id');
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'contratohistorico.*');
        $this->crud->addClause('where', 'contrato_id', '=', $contrato_id);
        foreach ($tps as $t){
            $this->crud->addClause('where', 'tipo_id', '<>', $t);
        }

        $this->crud->addButtonFromView('top', 'voltar', 'voltarcontrato', 'end');
        $this->crud->addButtonFromView('line', 'morecontratohistorico', 'morecontratohistorico', 'end');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contrato_editar')) ? $this->crud->allowAccess('update') : null;

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

        $categorias = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Categoria Contrato');
        })->where('descricao', '<>', 'A definir')->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $modalidades = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Modalidade Licitação');
        })->where('visivel',true)->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->where('descricao', '<>', 'Termo de Rescisão')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $campos = $this->Campos($fornecedores, $unidade, $categorias, $modalidades, $tipos, $contrato_id);
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in InstrumentoinicialRequest
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
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numero',
                'label' => 'Número do instrumento',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidadeOrigemHistorico',
                'label' => 'Unidade Gestora Origem', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidadeOrigemHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidadeHistorico',
                'label' => 'Unidade Gestora Atual', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidadeHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'unidades_requisitantes',
                'label' => 'Unidades Requisitantes',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getTipoHistorico',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipoHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getCategoriaHistorico',
                'label' => 'Categoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCategoriaHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getSubCategoriaHistorico',
                'label' => 'Subcategoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSubCategoriaHistorico', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
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
                'name' => 'processo',
                'label' => 'Processo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'objeto',
                'label' => 'Objeto',
                'type' => 'text',
                'limit' => 1000,
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
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
                'name' => 'data_assinatura',
                'label' => 'Data Assinatura',
                'type' => 'date',
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'data_publicacao',
                'label' => 'Data Publicação',
                'type' => 'date',
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

    public function Campos($fornecedores, $unidade, $categorias, $modalidades, $tipos, $contrato_id)
    {
        $campos = [
            [   // Hidden
                'name' => 'contrato_id',
                'type' => 'hidden',
                'default' => $contrato_id,
            ],
            [ // select_from_array
                'name' => 'receita_despesa',
                'label' => "Receita / Despesa",
                'type' => 'select_from_array',
                'options' => [
                    'D' => 'Despesa',
                    'R' => 'Receita',
                ],
                'default' => 'D',
                'allows_null' => false,
                'tab' => 'Dados Gerais',
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
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
            [
                // select_from_array
                'name' => 'tipo_id',
                'label' => "Tipo",
                'type' => 'select2_from_array',
                'options' => $tipos,
                'attributes' => [
                    'id' => 'tipo_contrato',
                ],
                'allows_null' => true,
                'tab' => 'Dados Gerais',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select_from_array
                'name' => 'categoria_id',
                'label' => "Categoria",
                'type' => 'select2_from_array',
                'options' => $categorias,
                'allows_null' => true,
                'tab' => 'Dados Gerais',
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [ // select2_from_ajax: 1-n relationship
                'name' => 'subcategoria_id', // the column that contains the ID of that connected entity
                'label' => "Subcategoria", // Table column heading
                'type' => 'select2_from_ajax',
                'model' => 'App\Models\OrgaoSubcategoria',
                'entity' => 'orgaosubcategoria', // the method that defines the relationship in your Model
                'attribute' => 'descricao', // foreign key attribute that is shown to user
                'data_source' => url('api/orgaosubcategoria'), // url to controller search function (with /{id} should return model)
                'placeholder' => 'Selecione...', // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies' => ['categoria_id'], // when a dependency changes, this select2 is reset to null
                'method'                    => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST)
                'tab' => 'Dados Gerais',
            ],
            [
                'name' => 'numero',
                'label' => 'Número do instrumento',
                'type' => 'numcontrato',
                'tab' => 'Dados Gerais',
            ],
            [
                'name' => 'processo',
                'label' => 'Número Processo',
                'type' => 'numprocesso',
                'tab' => 'Dados Gerais',
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
                'tab' => 'Dados Gerais',
            ],
//            [ // select_from_array
//                'name' => 'fornecedor_id',
//                'label' => "Fornecedor",
//                'type' => 'select2_from_array',
//                'options' => $fornecedores,
//                'allows_null' => true,
//                'tab' => 'Dados Gerais',
////                'default' => 'one',
//                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
//            ],
            [
                // 1-n relationship
                'label' => "Unidade Gestora Origem", // Table column heading
                'type' => "select2_from_ajax",
                'name' => 'unidadeorigem_id', // the column that contains the ID of that connected entity
                'entity' => 'unidadeorigem', // the method that defines the relationship in your Model
                'attribute' => "codigo", // foreign key attribute that is shown to user
                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_unidade',
                'model' => "App\Models\Unidade", // foreign key model
                'data_source' => url("api/unidade"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a Unidade", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
                'tab' => 'Dados Gerais',
            ],
            [ // select_from_array
                'name' => 'unidade_id',
                'label' => "Unidade Gestora",
                'type' => 'select2_from_array',
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
                'name' => 'unidades_requisitantes',
                'label' => 'Unidades Requisitantes',
                'type' => 'text',
                'tab' => 'Dados Gerais',
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
                'tab' => 'Dados Gerais',
//                'attributes' => [
//                    'disabled' => 'disabled',
//                ],
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],

            [   // Date
                'name' => 'data_assinatura',
                'label' => 'Data Assinatura',
                'type' => 'date',
                'tab' => 'Dados Contrato',
            ],
            [   // Date
                'name' => 'data_publicacao',
                'label' => 'Data Publicação',
                'type' => 'date',
                'tab' => 'Dados Contrato',
            ],
            [
                'name' => 'objeto',
                'label' => 'Objeto',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Contrato',
            ],
            [
                'name' => 'info_complementar',
                'label' => 'Informações Complementares',
                'type' => 'textarea',
                'attributes' => [
                    'onkeyup' => "maiuscula(this)"
                ],
                'tab' => 'Dados Contrato',
            ],
            [
                // 1-n relationship
                'label' => "Unidade Compra", // Table column heading
                'type' => "select2_from_ajax",
                'name' => 'unidadecompra_id', // the column that contains the ID of that connected entity
                'entity' => 'unidadecompra', // the method that defines the relationship in your Model
                'attribute' => "codigo", // foreign key attribute that is shown to user
                'attribute2' => "nomeresumido", // foreign key attribute that is shown to user
                'process_results_template' => 'gescon.process_results_unidade',
                'model' => "App\Models\Unidade", // foreign key model
                'data_source' => url("api/unidade"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Selecione a Unidade", // placeholder for the select
                'minimum_input_length' => 2, // minimum characters to type before querying results
                'tab' => 'Dados Contrato',
            ],
            [
                // select_from_array
                'name' => 'modalidade_id',
                'label' => "Modalidade Licitação",
                'type' => 'select2_from_array',
                'options' => $modalidades,
                'allows_null' => true,
                'tab' => 'Dados Contrato',
//                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'licitacao_numero',
                'label' => 'Número Licitação',
                'type' => 'numlicitacao',
                'tab' => 'Dados Contrato',
            ],
            [
                'name' => 'itens',
                'type' => 'itens_contrato_instrumento_inicial_list',
                'tab' => 'Itens do contrato',
            ],
            [   // Date
                'name' => 'vigencia_inicio',
                'label' => 'Data Vig. Início',
                'type' => 'date',
                'tab' => 'Vigência / Valores',
            ],
            [   // Date
                'name' => 'vigencia_fim',
                'label' => 'Data Vig. Fim',
                'type' => 'date',
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
                'tab' => 'Vigência / Valores',
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
        // your additional operations before save here
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
        $this->crud->removeColumn('tipo_id');
        $this->crud->removeColumn('categoria_id');
        $this->crud->removeColumn('unidade_id');
        $this->crud->removeColumn('fundamento_legal');
        $this->crud->removeColumn('modalidade_id');
        $this->crud->removeColumn('licitacao_numero');
//        $this->crud->removeColumn('data_assinatura');
//        $this->crud->removeColumn('data_publicacao');
        $this->crud->removeColumn('valor_inicial');
        $this->crud->removeColumn('valor_global');
        $this->crud->removeColumn('valor_parcela');
        $this->crud->removeColumn('valor_acumulado');
        $this->crud->removeColumn('situacao_siasg');
        $this->crud->removeColumn('contrato_id');
        $this->crud->removeColumn('receita_despesa');
        $this->crud->removeColumn('novo_valor_global');
        $this->crud->removeColumn('novo_num_parcelas');
        $this->crud->removeColumn('novo_valor_parcela');
        $this->crud->removeColumn('data_inicio_novo_valor');
        $this->crud->removeColumn('subcategoria');


        return $content;
    }
}
