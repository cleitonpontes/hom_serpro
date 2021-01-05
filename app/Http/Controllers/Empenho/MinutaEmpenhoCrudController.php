<?php

namespace App\Http\Controllers\Empenho;

use Alert;
use App\Forms\InserirFornecedorForm;
use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\SaldoContabil;
use App\Models\SfOrcEmpenhoDados;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\MinutaEmpenhoRequest as StoreRequest;
use App\Http\Requests\MinutaEmpenhoRequest as UpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;
use Redirect;
use Route;
use App\Http\Traits\Formatador;
use FormBuilder;

/**
 * Class MinutaEmpenhoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MinutaEmpenhoCrudController extends CrudController
{
    use Formatador;

    public function setup()
    {
        $this->minuta_id = $this->crud->getCurrentEntryId();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MinutaEmpenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/minuta');
        $this->crud->setEntityNameStrings('Minuta de Empenho', 'Minutas de Empenho');
        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->setShowView('vendor.backpack.crud.empenho.show');

        $this->crud->addButtonFromView('top', 'create', 'createbuscacompra');
        $this->crud->addButtonFromView('line', 'update', 'etapaempenho', 'end');
        $this->crud->addButtonFromView('line', 'atualizarsituacaominuta', 'atualizarsituacaominuta');
//        $this->crud->addButtonFromView('line', 'moreminuta', 'moreminuta', 'end');

        $this->crud->urlVoltar = route(
            'empenho.minuta.etapa.subelemento',
            ['minuta_id' => $this->minuta_id]
        );

        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');

        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->orderBy('updated_at', 'desc');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();

        $this->adicionaCampos($this->minuta_id);
        $this->adicionaColunas($this->minuta_id);

        // add asterisk for fields that are required in MinutaEmpenhoRequest
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
        $request->request->set('taxa_cambio', $this->retornaFormatoAmericano($request->taxa_cambio));
        $request->request->set('etapa', 7);

        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return Redirect::to('empenho/passivo-anterior/' . $this->minuta_id);
//        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->adicionaBoxItens($id);
        $this->adicionaBoxSaldo($id);

        $this->crud->removeColumn('tipo_empenhopor_id');
        $this->crud->removeColumn('situacao_id');
        $this->crud->removeColumn('unidade_id');
        $this->crud->removeColumn('compra_id');

        $this->crud->removeColumn('fornecedor_compra_id');
        $this->crud->removeColumn('fornecedor_empenho_id');
        $this->crud->removeColumn('saldo_contabil_id');

        $this->crud->removeColumn('tipo_empenho_id');
        $this->crud->removeColumn('amparo_legal_id');

        $this->crud->removeColumn('numero_empenho_sequencial');
        $this->crud->removeColumn('passivo_anterior');
        $this->crud->removeColumn('conta_contabil_passivo_anterior');
        $this->crud->removeColumn('tipo_minuta_empenho');

        return $content;
    }

    protected function adicionaCampos($minuta_id)
    {
        $this->adicionaCampoNumeroEmpenho();
        $this->adicionaCampoCipi();
        $this->adicionaCampoDataEmissao();
        $this->adicionaCampoTipoEmpenho();
        $this->adicionaCampoFornecedor();
        $this->adicionaCampoProcesso();
        $this->adicionaCampoAmparoLegal($minuta_id);
        $this->adicionaCampoTaxaCambio();
        $this->adicionaCampoLocalEntrega();
        $this->adicionaCampoDescricao();
    }

    protected function adicionaCampoNumeroEmpenho()
    {
        $this->crud->addField([
            'name' => 'numero_empenho_sequencial',
            'label' => 'Número Empenho',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'title' => 'Esse campo é opcional. Preencha caso sua unidade deseje controlar a numeração do empenho. Ao deixar o campo em branco, o sistema irá realizar o controle da numeração dos empenhos automaticamente.',
            ]
        ]);
    }

    protected function adicionaCampoCipi()
    {
        $this->crud->addField([
            'name' => 'numero_cipi',
            'label' => 'ID CIPI',
            'type' => 'text_cipi',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoDataEmissao()
    {
        $this->crud->addField([
            'name' => 'data_emissao',
            'label' => 'Data Emissão',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'title' => 'Somente data atual ou retroativa',

            ],
        ]);
    }

    protected function adicionaCampoTipoEmpenho()
    {
        $tipo_empenhos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo Minuta Empenho');
        })->where('visivel', false)->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $this->crud->addField([
            'name' => 'tipo_empenho_id',
            'label' => "Tipo Empenho",
            'type' => 'select2_from_array',
            'options' => $tipo_empenhos,
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoFornecedor()
    {
        //$form = $this->retonaFormModal();
        $this->crud->addField([
            'label' => "Credor",
            'type' => "select2_from_ajax_credor",
            'name' => 'fornecedor_empenho_id',
            'entity' => 'fornecedor_empenho',
            'attribute' => "cpf_cnpj_idgener",
            'attribute2' => "nome",
            'process_results_template' => 'gescon.process_results_fornecedor',
            'model' => "App\Models\Fornecedor",
            'data_source' => url("api/fornecedor"),
            'placeholder' => "Selecione o fornecedor",
            'minimum_input_length' => 2,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'form' => $this->retonaFormModal()
        ]);
    }

    protected function adicionaCampoProcesso()
    {
        $this->crud->addField([
            'name' => 'processo',
            'label' => 'Número Processo',
            'type' => 'numprocesso',
            'limit' => 20,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoAmparoLegal($minuta_id)
    {
        $modelo = MinutaEmpenho::find($minuta_id);

        $this->crud->addField([
            'name' => 'amparo_legal_id',
            'label' => "Amparo Legal",
            'type' => 'select2_from_array',
            'options' => $minuta_id ? $modelo->retornaAmparoPorMinuta() : [],
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoTaxaCambio()
    {
        $this->crud->addField([
            'name' => 'taxa_cambio',
            'label' => 'Taxa de Cambio',
            'type' => 'taxa_cambio',
            'attributes' => [
                'id' => 'taxa_cambio'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoLocalEntrega()
    {
        $this->crud->addField([
            'name' => 'local_entrega',
            'label' => 'Local de Entrega',
            'attributes' => [
                'onblur' => "maiuscula(this)"
            ]
        ]);
    }

    protected function adicionaCampoDescricao()
    {
        $this->crud->addField([
            'name' => 'descricao',
            'label' => 'Descrição / Observação',
            'type' => 'textarea',
            'attributes' => [
                'onblur' => "maiuscula(this)"
            ]
        ]);
    }

    /**
     * Configura a grid de visualização
     *
     */
    protected function adicionaColunas($minuta_id): void
    {
        $this->adicionaColunaUnidade();
        $this->adicionaColunaFornecedorEmpenho();

        $this->adicionaColunaTipoCompra();
        $this->adicionaColunaUnidadeCompra();
        $this->adicionaColunaModalidade();
        $this->adicionaColunaTipoEmpenhoPor();
        $this->adicionaColunaNumeroAnoCompra();

        $this->adicionaColunaTipoEmpenho();
        $this->adicionaColunaAmparoLegal();

        $this->adicionaColunaIncisoCompra();
        $this->adicionaColunaLeiCompra();
        $this->adicionaColunaValorTotal();


        $this->adicionaColunaMensagemSiafi();
        $this->adicionaColunaSituacao();
        $this->adicionaColunaCreatedAt();
        $this->adicionaColunaUpdatedAt();


        $this->adicionaColunaNumeroEmpenho();
        $this->adicionaColunaCipi();
        $this->adicionaColunaDataEmissao();
        $this->adicionaColunaProcesso();
        $this->adicionaColunaTaxaCambio();
        $this->adicionaColunaLocalEntrega();
        $this->adicionaColunaDescricao();
    }

    public function adicionaColunaSituacao(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getSituacao',
            'label' => 'Situação',
            'type' => 'model_function',
            'function_name' => 'getSituacao',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaMensagemSiafi(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'mensagem_siafi',
            'label' => 'Mensagem SIAFI',
            'type' => 'text',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    /**
     * Configura a coluna Unidade
     */

    public function adicionaColunaUnidade(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getUnidade',
            'label' => 'Unidade Gestora',
            'type' => 'model_function',
            'function_name' => 'getUnidade',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaCreatedAt(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'created_at',
            'label' => 'Criação em',
            'type' => 'datetime',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaUpdatedAt(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'updated_at',
            'label' => 'Atualizado em',
            'type' => 'datetime',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaUnidadeCompra(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getUnidadeCompra',
            'label' => 'UASG Compra',
            'type' => 'model_function',
            'function_name' => 'getUnidadeCompra',
            'priority' => 1,
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    public function adicionaColunaFornecedorEmpenho(): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getFornecedorEmpenho',
            'label' => 'Credor', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getFornecedorEmpenho', // the method in your Model
            'orderable' => true,
            'limit' => 100,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%$searchTerm%");
                $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
            },
        ]);
    }

    public function adicionaColunaTipoEmpenhoPor()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getTipoEmpenhoPor',
            'label' => 'Tipo de Minuta', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getTipoEmpenhoPor', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

        ]);
    }

    public function adicionaColunaTipoEmpenho()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getTipoEmpenho',
            'label' => 'Tipo de Empenho', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getTipoEmpenho', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
//                'searchLogic'   => function ($query, $column, $searchTerm) {
//                    $query->orWhere('cpf_cnpj_idgener', 'like', '%'.$searchTerm.'%');
//                    $query->orWhere('nome', 'like', '%'.$searchTerm.'%');
//                },

        ]);
    }

    public function adicionaColunaAmparoLegal()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'getAmparoLegal',
            'label' => 'Amparo Legal', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }


    public function adicionaColunaModalidade()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'compra_modalidade',
            'label' => 'Modalidade', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaValorTotal()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'valor_total',
            'label' => 'Valor Total', // Table column heading
            'type' => 'number',
            'prefix' => 'R$ ',
            'decimals' => 2,
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaTipoCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'tipo_compra',
            'label' => 'Tipo da Compra', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaNumeroAnoCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'numero_ano',
            'label' => 'Numero/Ano', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaIncisoCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'inciso',
            'label' => 'Inciso', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaColunaLeiCompra()
    {
        $this->crud->addColumn([
            'box' => 'compra',
            'name' => 'lei',
            'label' => 'Lei', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    public function adicionaBoxItens($minuta_id)
    {
        $modMinuta = MinutaEmpenho::find($minuta_id);
        $codigoitem = Codigoitem::find($modMinuta->tipo_empenhopor_id);

        if ($codigoitem->descres == 'CON') {
//            join('contrato_minuta_empenho_pivot',
//                'contrato_minuta_empenho_pivot.minuta_empenho_id',
//                '=',
//                'minutaempenhos.id'
//            )->join('contratos', 'contratos.id','=','contrato_minuta_empenho_pivot.contrato_id')
//                ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
//                ->where('minutaempenhos.id', $minuta_id)

            $itens = ContratoItemMinutaEmpenho::join(
                'contratoitens',
                'contratoitens.id',
                '=',
                'contrato_item_minuta_empenho.contrato_item_id'
            )

                ->join('minutaempenhos','minutaempenhos.id', '=', 'contrato_item_minuta_empenho.minutaempenho_id')
                ->join('contratos', 'contratos.id', '=', 'minutaempenhos.contrato_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'contratoitens.tipo_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'contratoitens.catmatseritem_id')
                ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
                ->where('contrato_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->select([
                    DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                    DB::raw('fornecedores.nome AS "Fornecedor"'),
                    DB::raw('codigoitens.descricao AS "Tipo do Item"'),
                    DB::raw('catmatseritens.codigo_siasg AS "Código do Item"'),
                    DB::raw('contratoitens.numero_item_compra AS "Número do Item"'),
                    DB::raw('catmatseritens.descricao AS "Descrição"'),
                    DB::raw('contratoitens.descricao_complementar AS "Descrição Detalhada"'),
                    DB::raw('contrato_item_minuta_empenho.quantidade AS "Quantidade"'),
                    DB::raw('contrato_item_minuta_empenho.Valor AS "Valor Total do Item"'),
                ])
                ->get()->toArray();

        }

        if ($codigoitem->descres == 'COM') {
            $itens = CompraItemMinutaEmpenho::join('compra_items', 'compra_items.id', '=', 'compra_item_minuta_empenho.compra_item_id')
                ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_item_minuta_empenho.compra_item_id')
                ->join('naturezasubitem', 'naturezasubitem.id', '=', 'compra_item_minuta_empenho.subelemento_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'compra_items.catmatseritem_id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
                //            ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_items.id')
                ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
                ->where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->where('compra_item_minuta_empenho.remessa', 0)
                ->select([
                    DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                    DB::raw('fornecedores.nome AS "Fornecedor"'),
                    DB::raw('codigoitens.descricao AS "Tipo do Item"'),
                    DB::raw('catmatseritens.codigo_siasg AS "Código do Item"'),
                    DB::raw('compra_items.numero AS "Número do Item"'),
                    DB::raw('catmatseritens.descricao AS "Descrição"'),
                    DB::raw('compra_items.descricaodetalhada AS "Descrição Detalhada"'),
                    DB::raw('naturezasubitem.codigo || \' - \' || naturezasubitem.descricao AS "ND Detalhada"'),
                    DB::raw('compra_item_fornecedor.valor_unitario AS "Valor unitário"'),
                    DB::raw('compra_item_minuta_empenho.quantidade AS "Quantidade"'),
                    DB::raw('compra_item_minuta_empenho.Valor AS "Valor Total do Item"'),


                ])
                ->get()->toArray();
            //        ;dd($itens->getBindings(),$itens->toSql());
        }

        $this->crud->addColumn([
            'box' => 'itens',
            'name' => 'itens',
            'label' => 'itens', // Table column heading
//            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
            'values' => $itens
        ]);
    }

    public function adicionaBoxSaldo($minuta_id)
    {

        $saldo = SaldoContabil::join('minutaempenhos', 'minutaempenhos.saldo_contabil_id', '=', 'saldo_contabil.id')
            ->select([
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,1,1) AS "Esfera"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,2,6) AS "PTRS"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,8,10) AS "Fonte"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,18,6) AS "ND"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,24,8) AS "UGR"'),
                DB::raw('SUBSTRING(saldo_contabil.conta_corrente,32,11) AS "Plano Interno"'),
                DB::raw('TO_CHAR(saldo_contabil.saldo,\'999G999G000D99\') AS "Crédito orçamentário"'),
                DB::raw('TO_CHAR(minutaempenhos.valor_total,\'999G999G000D99\') AS "Utilizado"'),
                DB::raw('TO_CHAR(saldo_contabil.saldo - minutaempenhos.valor_total, \'999G999G000D99\')  AS "Saldo"'),

            ])
            ->where('minutaempenhos.id', $minuta_id)
            ->get()
            ->toArray();

        $this->crud->addColumn([
            'box' => 'saldo',
            'name' => 'saldo',
            'label' => 'saldo', // Table column heading
//            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false, // no point, since it's a large text
            'visibleInModal' => false, // would make the modal too big
            'visibleInExport' => false, // not important enough
            'visibleInShow' => true, // sure, why not
            'values' => $saldo
        ]);
    }

    public function adicionaColunaNumeroEmpenho()
    {
    }

    public function adicionaColunaCipi()
    {
    }

    public function adicionaColunaDataEmissao()
    {
    }


    public function adicionaColunaProcesso()
    {
    }


    public function adicionaColunaTaxaCambio()
    {
    }

    public function adicionaColunaLocalEntrega()
    {
    }

    public function adicionaColunaDescricao()
    {
    }

    public function retonaFormModal()
    {
        return FormBuilder::create(InserirFornecedorForm::class, [
            'id' => 'form_modal'
        ]);
    }

    public function inserirFornecedorModal(Request $request)
    {

        DB::beginTransaction();
        try {
            $fornecedor = Fornecedor::firstOrCreate(
                ['cpf_cnpj_idgener' => $request->cpf_cnpj_idgener],
                [
                    'tipo_fornecedor' => $request->fornecedor,
                    'nome' => $request->nome
                ]
            );
            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }
        return $fornecedor;

//        $conta_corrente = $this->retornaContaCorrente($request);
//        $saldo = $request->get('valor');
//        $unidade_id = $request->get('unidade_id');
//        $ano = date('Y');
//        $contacontabil = config('app.conta_contabil_credito_disponivel');
//        $modSaldo = new SaldoContabil();
//        $modSaldo->unidade_id = $unidade_id;
//        $modSaldo->ano = $ano;
//        $modSaldo->conta_contabil = $contacontabil;
//        $modSaldo->conta_corrente = $conta_corrente;
//        $modSaldo->saldo = $this->retornaFormatoAmericano($saldo);
//        $modSaldo->save();
//
//        return redirect()->route(
//            'empenho.minuta.etapa.saldocontabil',
//            [
//                'minuta_id' => $request->get('minuta_id')
//            ]
//        );
    }

    public function executarAtualizacaoSituacaoMinuta($id)
    {
        $minuta = MinutaEmpenho::find($id);

        if ($minuta->situacao->descricao == 'ERRO') {
            DB::beginTransaction();
            try {
                $situacao = Codigoitem::wherehas('codigo', function ($q) {
                    $q->where('descricao', '=', 'Situações Minuta Empenho');
                })
                    ->where('descricao', 'EM PROCESSAMENTO')
                    ->first();
                $minuta->situacao_id = $situacao->id;
                $minuta->save();

                $modSfOrcEmpenhoDados = SfOrcEmpenhoDados::where('minutaempenho_id', $id)->first();

                $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
                $modSfOrcEmpenhoDados->save();

                DB::commit();
            } catch (Exception $exc) {
                DB::rollback();
            }

            Alert::success('Situação da minuta alterada com sucesso!')->flash();
            return redirect('/empenho/minuta');
        } else {
            Alert::warning('Situação da minuta não pode ser alterada!')->flash();
            return redirect('/empenho/minuta');
        }
    }
}
