<?php

namespace App\Http\Controllers\Gescon;

use App\Jobs\AlertaContratoJob;
use App\Models\Codigoitem;
use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\Fornecedor;
use App\Models\Unidade;
use App\Notifications\RotinaAlertaContratoNotification;
use App\PDF\Pdf;
use App\XML\ApiSiasg;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoRequest as StoreRequest;
use App\Http\Requests\ContratoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ContratoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanell $crud
 */
class ContratoCrudController extends CrudController
{
    /**
     *
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contrato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato');
        $this->crud->setEntityNameStrings('Contrato', 'Contratos');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause('select', 'contratos.*');

//        $this->crud->addButtonFromView('top', 'notificausers', 'notificausers', 'end');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Global
        |--------------------------------------------------------------------------
        */
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();
//        $this->crud->disableResponsiveTable();

        $this->crud->addButtonFromView('top', 'siasg', 'siasg', 'end');
        $this->crud->addButtonFromView('line', 'extratocontrato', 'extratocontrato', 'beginning');
        $this->crud->addButtonFromView('line', 'morecontrato', 'morecontrato', 'end');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contrato_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contrato_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Collumns Table
        |--------------------------------------------------------------------------
        */

        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Campos Formulário
        |--------------------------------------------------------------------------
        */

        $fornecedores = Fornecedor::select(DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"), 'id')
            ->orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();

        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $categorias = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Categoria Contrato');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $modalidades = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Modalidade Licitação');
        })->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();


        $campos = $this->Campos($fornecedores, $unidade, $categorias, $modalidades, $tipos);
        $this->crud->addFields($campos);

    }

    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'getReceitaDespesa',
                'label' => 'Receita / Despesa', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getReceitaDespesa', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'numero',
                'label' => 'Número Contrato',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidadeOrigem',
                'label' => 'Unidade Gestora Origem', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidadeOrigem', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getUnidade',
                'label' => 'Unidade Gestora Atual', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getUnidade', // the method in your Model
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
                'name' => 'getTipo',
                'label' => 'Tipo', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getTipo', // the method in your Model

                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getCategoria',
                'label' => 'Categoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCategoria', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'getSubCategoria',
                'label' => 'Subcategoria', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getSubCategoria', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
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
                    $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                    $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
                },
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
                'name' => 'formatVlrGlobal',
                'label' => 'Valor Global', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrGlobal', // the method in your Model
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
                'name' => 'formatVlrParcela',
                'label' => 'Valor Parcela', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrParcela', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatVlrAcumulado',
                'label' => 'Valor Acumulado', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatVlrAcumulado', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
            [
                'name' => 'formatTotalDespesasAcessorias',
                'label' => 'Total Despesas Acessórias', // Table column heading
                'type' => 'model_function',
                'function_name' => 'formatTotalDespesasAcessorias', // the method in your Model
                'orderable' => true,
                'visibleInTable' => false, // no point, since it's a large text
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

    public function Campos($fornecedores, $unidade, $categorias, $modalidades, $tipos)
    {
        $campos = [
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
                'method' => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST)
                'tab' => 'Dados Gerais',
            ],
            [
                'name' => 'numero',
                'label' => 'Número Contrato',
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
                'label' => "Unidade Gestora Atual",
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

            [ // select_from_array
                'name' => 'fornecedor_id',
                'label' => "Fornecedor",
                'type' => 'select2_from_array',
                'options' => $fornecedores,
                'allows_null' => true,
                'tab' => 'Dados Contrato',
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
                'label' => 'Núm. Parcelas',
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
        $this->crud->removeColumn('info_complementar');
        $this->crud->removeColumn('fundamento_legal');
        $this->crud->removeColumn('modalidade_id');
        $this->crud->removeColumn('licitacao_numero');
        $this->crud->removeColumn('data_assinatura');
        $this->crud->removeColumn('data_publicacao');
        $this->crud->removeColumn('valor_inicial');
        $this->crud->removeColumn('valor_global');
        $this->crud->removeColumn('valor_parcela');
        $this->crud->removeColumn('valor_acumulado');
        $this->crud->removeColumn('situacao_siasg');
        $this->crud->removeColumn('receita_despesa');
        $this->crud->removeColumn('subcategoria_id');


        return $content;
    }

    public function extratoPdf(int $contrato_id)
    {
        $contrato = Contrato::find($contrato_id);

        $pdf = new Pdf("P", "mm", "A4");
        $pdf->SetTitle("Extrato Contrato", 1);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        //Dados Contratos
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(0, 5, utf8_decode("Dados do Contrato") . ' - Contrato num.: ' . utf8_decode($contrato->numero) . ' - UG: ' . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido), 0, 0, 'C');

        $pdf->SetY("35");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(28, 5, utf8_decode("Número Contrato: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode($contrato->numero), 0, 0, 'L');

        $pdf->SetY("40");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(33, 5, utf8_decode("CNPJ/CPF/ID Genérico: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode($contrato->fornecedor->cpf_cnpj_idgener), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(18, 5, utf8_decode("Fornecedor: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode($contrato->fornecedor->nome), 0, 0, 'L');

        $pdf->SetY("45");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 5, utf8_decode("Processo Núm.: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, utf8_decode($contrato->processo), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(18, 5, utf8_decode("UG Recurso: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nome), 0, 0, 'L');

        $pdf->SetY("50");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(23, 5, utf8_decode("Data Assinatura: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 5, utf8_decode(implode("/", array_reverse(explode("-", $contrato->data_assinatura)))), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(23, 5, utf8_decode("Tipo do Contrato: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode($contrato->tipo->descricao), 0, 0, 'L');

        $pdf->SetY("55");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(23, 5, utf8_decode("Tipo Licitação: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 5, utf8_decode($contrato->modalidade->descricao), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, utf8_decode("Número Licitação: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode($contrato->licitacao_numero), 0, 0, 'L');

        $pdf->SetY("60");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 5, utf8_decode("Data Vigência Início: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 5, utf8_decode(implode("/", array_reverse(explode("-", $contrato->vigencia_inicio)))), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 5, utf8_decode("Data Vigência Fim: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 5, utf8_decode(implode("/", array_reverse(explode("-", $contrato->vigencia_fim)))), 0, 0, 'L');

        $pdf->SetY("65");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(17, 5, utf8_decode("Valor Global: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode(number_format($contrato->valor_global, 2, ',', '.')), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(21, 5, utf8_decode("Núm. Parcelas: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 5, utf8_decode(number_format($contrato->num_parcelas, 0, '', '.')), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(18, 5, utf8_decode("Valor Parcial: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode(number_format($contrato->valor_parcela, 2, ',', '.')), 0, 0, 'L');

        $pdf->SetY("70");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(24, 5, utf8_decode("Valor Acumulado: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode(number_format($contrato->valor_acumulado, 2, ',', '.')), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(33, 5, utf8_decode("Total Desp. Acessórias: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode(number_format($contrato->total_despesas_acessorias, 2, ',', '.')), 0, 0, 'L');

        $pdf->SetY("75");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode("Objeto: "), 0, 0, 'L');
        $pdf->SetY("80");
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(0, 5, utf8_decode($contrato->objeto), 0, 'J');

        //numero de caracteres fonte 9 por linha 100
        $pdf->SetY($this->calculaLinhasMultiCell(strlen($contrato->objeto), '80'));
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode("Informação Complementar: "), 0, 0, 'L');
        $pdf->SetY($this->calculaLinhasMultiCell(strlen($contrato->objeto), '80') + 5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(0, 5, utf8_decode($contrato->info_complementar), 0, 'J');

        //Histórico de Contrato
        $pdf->AddPage();
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(0, 5
            , utf8_decode("Histórico do Contrato") . ' - Contrato num.: '
            . utf8_decode($contrato->numero) . ' - UG: '
            . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido)
            , 0, 0, 'C'
        );

        $pdf->SetY(35);
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(0, 5, utf8_decode("Histórico"));

        $pdf->SetY(40);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(21, 5, utf8_decode("Data Assinatura"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Número"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Observação"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Tipo"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Data Início"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Data Fim"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Valor Global"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Parcelas"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Valor Parcela"), 1, 0, 'C');

        $row_resp = 45;
        $historico = $contrato->historico()->get();

        foreach ($historico as $registro) {
            if ($row_resp >= 245) {
                $row_resp = 40;
                $pdf->AddPage();
                $pdf->SetY($row_resp);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(21, 5, utf8_decode("Data Assinatura"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Número"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Observação"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Tipo"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Data Início"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Data Fim"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Valor Global"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Parcelas"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Valor Parcela"), 1, 0, 'C');
                $row_resp += 5;
            }

            $pdf->SetFont('Arial', '', 7);
            $pdf->SetY($row_resp);
            $lines = $pdf->NbLines(21, utf8_decode($registro->observacao)) * 5;
            $pdf->Cell(21, $lines, implode('/',
                    array_reverse(explode('-', $registro->data_assinatura)))
                , 1, 0, 'L');
            $pdf->Cell(21, $lines, $registro->numero, 1, 0, 'L');
            $pdf->MultiCell(21, 5, utf8_decode($registro->observacao), 1);
            $pdf->SetXY($pdf->GetX() + (3 * 21), $row_resp);
//            $pdf->SetX($pdf->GetX()+(3*21));
            $pdf->Cell(21, $lines, utf8_decode($registro->tipo()->first()->descricao), 1, 0, 'C');
            $pdf->Cell(21, $lines, implode('/', array_reverse(explode('-', $registro->vigencia_inicio)))
                , 1, 0, 'C');
            $pdf->Cell(21, $lines, implode('/', array_reverse(explode('-', $registro->vigencia_fim)))
                , 1, 0, 'C');
            $pdf->Cell(21, $lines, number_format($registro->valor_global, 2, ',', ".")
                , 1, 0, 'R');
            $pdf->Cell(21, $lines, $registro->num_parcelas, 1, 0, 'R');
            $pdf->Cell(21, $lines, number_format($registro->valor_parcela, 2, ',', ".")
                , 1, 0, 'R');

            $row_resp += $lines;

        }

        //responsaveis do contrato
        //Responsáveis
        $pdf->AddPage();
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(0, 5, utf8_decode("Responsáveis") . ' - Contrato num.: ' . utf8_decode($contrato->numero) . ' - UG: ' . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido), 0, 0, 'C');

        //busca responsaveis por situacao
        $responsaveis_ativos = $contrato->responsaveis()->where('situacao', true)->get();
        $responsaveis_inativos = $contrato->responsaveis()->where('situacao', false)->get();

        //ativos
        $pdf->SetY("35");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(28, 5, utf8_decode("Ativos"), 0, 0, 'L');

        $row_resp = 35 + 5;

        foreach ($responsaveis_ativos as $ativo) {
            if ($row_resp >= 260) {
                $row_resp = 35;
                $pdf->AddPage();
            }

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("CPF / Nome: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 5, utf8_decode($ativo->user->cpf . ' - ' . $ativo->user->name), 'T', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Função: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode($ativo->funcao->descricao), 'T', 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Portaria: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 5, utf8_decode($ativo->portaria), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 5, utf8_decode("Telefone Fixo: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(48, 5, utf8_decode($ativo->telefone_fixo), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, 5, utf8_decode("Telefone Celular: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode($ativo->telefone_celular), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Unidade: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(($ativo->instalacao_id) ? $ativo->instalacao->nome : ''), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data Início: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode(implode("/", array_reverse(explode("-", $ativo->data_inicio)))), 'B', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data Fim: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(implode("/", array_reverse(explode("-", $ativo->data_fim)))), 'B', 0, 'L');

            $row_resp = $row_resp + 5;
        }

        //inativos
        $row_resp = $row_resp + 5;
        $pdf->SetY($row_resp);
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(28, 5, utf8_decode("Inativos"), 0, 0, 'L');
        $row_resp = $row_resp + 5;
        foreach ($responsaveis_inativos as $inativo) {
            if ($row_resp >= 260) {
                $row_resp = 35;
                $pdf->AddPage();
            }

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("CPF / Nome: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 5, utf8_decode($inativo->user->cpf . ' - ' . $inativo->user->name), 'T', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Função: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode($inativo->funcao->descricao), 'T', 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Portaria: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 5, utf8_decode($inativo->portaria), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 5, utf8_decode("Telefone Fixo: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(48, 5, utf8_decode($inativo->telefone_fixo), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, 5, utf8_decode("Telefone Celular: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode($inativo->telefone_celular), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Unidade: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(($inativo->instalacao_id) ? $inativo->instalacao->nome : ''), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data Início: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode(implode("/", array_reverse(explode("-", $inativo->data_inicio)))), 'B', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data Fim: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(implode("/", array_reverse(explode("-", $inativo->data_fim)))), 'B', 0, 'L');

            $row_resp = $row_resp + 5;
        }

        //execuçao orcamentaria e financeira - empenhos
        $pdf->AddPage();
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(0, 5, utf8_decode("Execução Orçamentária e Financeira") . ' - Contrato num.: ' . utf8_decode($contrato->numero) . ' - UG: ' . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido), 0, 0, 'C');

        $pdf->SetY("35");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(28, 5, utf8_decode("Empenhos"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode("R$"), 0, 0, 'R');

        $empenhos = $contrato->empenhos()->get();

        $pdf->SetY(40);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(21, 5, utf8_decode("Número"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Empenhado"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("A Liquidar"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Liquidado"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Pago"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP Inscr."), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP A Liq."), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP Liquidado"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP Pago"), 1, 0, 'C');

        $t_empenhado = 0;
        $t_aliquidar = 0;
        $t_liquidado = 0;
        $t_pago = 0;
        $t_rpinscrito = 0;
        $t_rpaliquidar = 0;
        $t_rpliquidado = 0;
        $t_rppago = 0;

        $row_resp = 40 + 5;

        foreach ($empenhos as $empenho) {

            if ($row_resp >= 260) {
                $row_resp = 35;
                $pdf->AddPage();
                $pdf->SetY($row_resp);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(21, 5, utf8_decode("Número"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Empenhado"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("A Liquidar"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Liquidado"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Pago"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP Inscr."), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP A Liq."), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP Liquidado"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP Pago"), 1, 0, 'C');
                $row_resp += 5;
            }

            $t_empenhado += $empenho->empenho->empenhado;
            $t_aliquidar += $empenho->empenho->aliquidar;
            $t_liquidado += $empenho->empenho->liquidado;
            $t_pago += $empenho->empenho->pago;
            $t_rpinscrito += $empenho->empenho->rpinscrito;
            $t_rpaliquidar += $empenho->empenho->rpaliquidar;
            $t_rpliquidado += $empenho->empenho->rpliquidado;
            $t_rppago += $empenho->empenho->rppago;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(21, 5, utf8_decode($empenho->empenho->numero), 1, 0, 'L');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->empenhado, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->aliquidar, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->liquidado, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->pago, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rpinscrito, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rpaliquidar, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rpliquidado, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rppago, 2, ',', ".")), 1, 0, 'R');

            $row_resp += 5;

        }

        $pdf->SetY($row_resp);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(21, 5, utf8_decode("Total"), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_empenhado, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_aliquidar, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_liquidado, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_pago, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rpinscrito, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rpaliquidar, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rpliquidado, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rppago, 2, ',', ".")), 1, 0, 'R');

        $nome_arquivo = str_replace('/', '', $contrato->numero) . ' - ' . str_replace(' ', '_', $contrato->fornecedor->nome) . '.pdf';

        $pdf->Output('D', $nome_arquivo);

    }

    private function calculaLinhasMultiCell($qtdcaracter, $ultimamedida)
    {
        $div = $qtdcaracter / 100;
        $ndiv = explode('.', $div);
        $linha = $ndiv[0] + 2;
        $tam = $linha * 5;
        $tamanho = $ultimamedida + $tam;
        return $tamanho;
    }

    public function notificaUsers()
    {

        $alerta_mensal = new AlertaContratoJob();
//        $alerta_mensal->emailDiario();
//        $alerta_mensal->extratoMensal();

        return redirect()->back();
    }

}
