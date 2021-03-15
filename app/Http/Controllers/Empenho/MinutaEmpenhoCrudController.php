<?php

namespace App\Http\Controllers\Empenho;

use Alert;
use App\Forms\InserirFornecedorForm;
use App\Http\Requests\MinutaEmpenhoRequest as StoreRequest;
use App\Http\Requests\MinutaEmpenhoRequest as UpdateRequest;
use App\Http\Traits\CompraTrait;
use App\Http\Traits\Formatador;
use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\MinutaEmpenhoRemessa;
use App\Models\SaldoContabil;
use App\Models\SfOrcEmpenhoDados;
use App\Repositories\Base;
use App\XML\Execsiafi;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use FormBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Route;

/**
 * Class MinutaEmpenhoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MinutaEmpenhoCrudController extends CrudController
{
    use Formatador;
    use CompraTrait;

    public function setup()
    {
//        if (!backpack_user()->can('empenho_minuta_acesso ')) { //alterar para novo grupo de Administrador Orgão
//            abort('403', config('app.erro_permissao'));
//        }

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
        $this->crud->addButtonFromView('line', 'atualizarsituacaominuta', 'atualizarsituacaominuta', 'beginning');
        $this->crud->addButtonFromView('line', 'deletarminuta', 'deletarminuta', 'end');
        $this->crud->addButtonFromView('line', 'moreminuta', 'moreminuta', 'end');

        $this->crud->urlVoltar = route(
            'empenho.minuta.etapa.subelemento',
            ['minuta_id' => $this->minuta_id]
        );

        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');

        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->addClause(
            'leftJoin',
            'fornecedores',
            'fornecedores.id',
            '=',
            'minutaempenhos.fornecedor_empenho_id'
        );
        $this->crud->addClause('leftJoin', 'codigoitens', 'codigoitens.id', '=', 'minutaempenhos.tipo_empenhopor_id');
        $this->crud->addClause('leftJoin', 'compras', 'compras.id', '=', 'minutaempenhos.compra_id');
        $this->crud->addClause('select', 'minutaempenhos.*', 'compras.modalidade_id');
        $this->crud->orderBy('minutaempenhos.updated_at', 'desc');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();

        $this->adicionaCampos($this->minuta_id);
        $this->adicionaColunas($this->minuta_id);
        $this->aplicaFiltros();

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
        $rota = 'empenho/passivo-anterior/' . $this->minuta_id;
        $conta_id = session('conta_id') ?? '';
        if ($conta_id) {
            $rota = route('empenho.crud.passivo-anterior.edit', ['minuta_id' => $conta_id]);
        }
        // your additional operations before save here
        $request->request->set('taxa_cambio', $this->retornaFormatoAmericano($request->taxa_cambio));
        $request->request->set('etapa', 7);

        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return Redirect::to($rota);
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
                'title' => 'Esse campo é opcional. Preencha caso sua unidade deseje' .
                    ' controlar a numeração do empenho. Ao deixar o campo em branco,' .
                    ' o sistema irá realizar o controle da numeração dos empenhos automaticamente.',
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
                'class' => 'form-group col-md-6',
                'title' => 'Formato padrão: 99.99-99',
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
                $query->orWhere('fornecedores.cpf_cnpj_idgener', 'ilike', "%$searchTerm%");
                $query->orWhere('fornecedores.nome', 'ilike', "%" . ($searchTerm) . "%");
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
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('codigoitens.descricao', 'ilike', '%' . $searchTerm . '%');
            },

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
//            'searchLogic'   => function ($query, $column, $searchTerm) {
//                $query->orWhere('compras.modalidade_id', '=', 'codigoitens.id', function ($q) use ($column, $searchTerm) {
//                })->where('codigoitens.descricao', 'ilike', '%' . $searchTerm . '%');
//            },
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
        $fornecedor_id = $modMinuta->fornecedor_empenho_id;
        $fornecedor_compra_id = $modMinuta->fornecedor_compra_id;

        if ($codigoitem->descricao === 'Contrato') {
            $itens = ContratoItemMinutaEmpenho::join(
                'contratoitens',
                'contratoitens.id',
                '=',
                'contrato_item_minuta_empenho.contrato_item_id'
            )
                ->join('minutaempenhos', 'minutaempenhos.id', '=', 'contrato_item_minuta_empenho.minutaempenho_id')
                ->join('contratos', 'contratos.id', '=', 'minutaempenhos.contrato_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'contratoitens.tipo_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'contratoitens.catmatseritem_id')
                ->join('fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id')
                ->join(
                    'minutaempenhos_remessa',
                    'minutaempenhos_remessa.id',
                    '=',
                    'contrato_item_minuta_empenho.minutaempenhos_remessa_id'
                )
                ->where('contrato_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->where('minutaempenhos_remessa.remessa', 0)
                ->distinct()
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
                    'contrato_item_minuta_empenho.numseq'
                ])
                ->orderBy('contrato_item_minuta_empenho.numseq', 'asc')
                ->get()->toArray();
        }

        if ($codigoitem->descricao === 'Compra' || $codigoitem->descricao === 'Suprimento') {
            $itens = CompraItemMinutaEmpenho::join(
                'compra_items',
                'compra_items.id',
                '=',
                'compra_item_minuta_empenho.compra_item_id'
            )
                ->join(
                    'compra_item_fornecedor',
                    'compra_item_fornecedor.compra_item_id',
                    '=',
                    'compra_items.id'
                )
                ->join('naturezasubitem', 'naturezasubitem.id', '=', 'compra_item_minuta_empenho.subelemento_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'compra_items.catmatseritem_id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
                ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
                ->join(
                    'minutaempenhos_remessa',
                    'minutaempenhos_remessa.id',
                    '=',
                    'compra_item_minuta_empenho.minutaempenhos_remessa_id'
                )
                ->where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->where('compra_item_unidade.unidade_id', $modMinuta->unidade_id)
                ->where('minutaempenhos_remessa.remessa', 0)
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
                    'compra_item_minuta_empenho.numseq'
                ])
                ->orderBy('compra_item_minuta_empenho.numseq', 'asc');
            $itens = $this->setCondicaoFornecedor(
                $modMinuta,
                $itens,
                $codigoitem->descricao,
                $fornecedor_id,
                $fornecedor_compra_id
            );

//            $itens->where('compra_item_unidade.fornecedor_id', $fornecedor_compra_id)
//                  ->where('compra_item_fornecedor.fornecedor_id', $fornecedor_compra_id);

            $itens = $itens->get()->toArray();
        }

        $this->crud->addColumn([
            'box' => 'itens',
            'name' => 'itens',
            'label' => 'itens', // Table column heading
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

    protected function aplicaFiltros()
    {
        $this->aplicaFiltroSituacao();
        $this->aplicaFiltroModalidade();
    }

    protected function aplicaFiltroSituacao()
    {
        $this->crud->addFilter([
            'name' => 'getSituacao',
            'label' => 'Situação',
            'type' => 'select2_multiple',

        ], [
            217 => 'EMPENHO EMITIDO',
            215 => 'EM PROCESSAMENTO',
            214 => 'EM ANDAMENTO',
            218 => 'EMPENHO CANCELADO',
            216 => 'ERRO',
        ], function ($value) {
            $this->crud->addClause(
                'whereIn',
                'minutaempenhos.situacao_id',
                json_decode($value)
            );
        });
    }

    protected function aplicaFiltroModalidade()
    {
        $this->crud->addFilter([
            'name' => 'compra_modalidade',
            'label' => 'Modalidade',
            'type' => 'select2_multiple',

        ], [
            76 => '05 - Pregão',
            73 => '01 - Convite',
            77 => '02 - Tomada de Preços',
            75 => '07 - Inexigibilidade',
            72 => '20 - Concurso',
            74 => '06 - Dispensa',
            71 => '03 - Concorrência',
            184 => '22 - Tomada de Preços por Técnica e Preço',
            185 => '33 - Concorrência por Técnica e Preço',
            186 => '44 - Concorrência Internacional por Técnica e Preço',
            187 => '04 - Concorrência Internacional',
            160 => '99 - Regime Diferenciado de Contratação',

        ], function ($value) {
            $this->crud->addClause(
                'whereIn',
                'compras.modalidade_id',
                json_decode($value)
            );
        });
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
    }

    public function executarAtualizacaoSituacaoMinuta($id)
    {
        $minuta = MinutaEmpenho::find($id);
        $date_time = \DateTime::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));

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

                $modSfOrcEmpenhoDados = SfOrcEmpenhoDados::where('minutaempenho_id', $id)
                    ->where('alteracao', false)
                    ->latest()
                    ->first();

                $remessa = MinutaEmpenhoRemessa::find($modSfOrcEmpenhoDados->minutaempenhos_remessa_id);
                if (!$remessa->sfnonce) {
                    $base = new Base();
                    $remessa->sfnonce = $base->geraNonceSiafiEmpenho($remessa->minutaempenho_id, $remessa->id);
                    $remessa->save();
                }

                if ($modSfOrcEmpenhoDados->sfnonce != $remessa->sfnonce) {
                    $modSfOrcEmpenhoDados->sfnonce = $remessa->sfnonce;
                }
                $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
                $modSfOrcEmpenhoDados->save();

                DB::commit();
                Alert::success('Situação da minuta alterada com sucesso!')->flash();
                return redirect('/empenho/minuta');
            } catch (Exception $exc) {
                DB::rollback();
            }
        }

        if($minuta->situacao->descricao == 'EM PROCESSAMENTO'){
            $updated_at = \DateTime::createFromFormat('Y-m-d H:i:s', $minuta->updated_at)->modify('+15 minutes');
            if($date_time < $updated_at){
                Alert::warning('Situação da minuta não pode ser alterada, tente novamente em 15 minutos!')->flash();
                return redirect('/empenho/minuta');
            }

            Alert::success('Minuta será processada novamente, por favor aguarde!')->flash();
            return redirect('/empenho/minuta');
        }


        Alert::warning('Situação da minuta não pode ser alterada!')->flash();
        return redirect('/empenho/minuta');
    }

    public function deletarMinuta($id)
    {
        $minuta = MinutaEmpenho::find($id);

        if ($minuta->situacao_descricao == 'ERRO' || $minuta->situacao_descricao == 'EM ANDAMENTO') {
            DB::beginTransaction();
            try {
                if ($minuta->empenho_por === 'Compra') {
                    $cime = $minuta->compraItemMinutaEmpenho();
                    $cime_deletar = $cime->get();
                    $cime->delete();

                    foreach ($cime_deletar as $item) {
                        $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $item->compra_item_id)
                            ->where('unidade_id', session('user_ug_id'))
                            ->first();
                        $compraItemUnidade->quantidade_saldo =
                            $this->retornaSaldoAtualizado($item->compra_item_id)->saldo;
                        $compraItemUnidade->save();
                    }
                    $minuta->forceDelete();
                    DB::commit();
                    Alert::success('Minuta Deletada com sucesso!')->flash();
                    return redirect($this->crud->route);
                }
                // Deletar minuta do contrato
                $minuta->forceDelete();
                DB::commit();

                Alert::success('Minuta Deletada com sucesso!')->flash();
                return redirect($this->crud->route);
            } catch (Exception $exc) {
                DB::rollback();
                Alert::error('Erro! Tente novamente mais tarde!')->flash();
                return redirect($this->crud->route);
            }

            Alert::success('Situação da minuta alterada com sucesso!')->flash();
            return redirect('/empenho/minuta');
        }
        Alert::warning('Operação não permitida!')->flash();
        return redirect($this->crud->route);
    }
}
