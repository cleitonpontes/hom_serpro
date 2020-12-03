<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Requests\MinutaAlteracaoRequest as StoreRequest;
use App\Http\Requests\MinutaAlteracaoRequest as UpdateRequest;
use App\Http\Traits\Formatador;
use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\Naturezasubitem;
use App\Models\SaldoContabil;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Route;
use Yajra\DataTables\DataTables;
use App\Http\Traits\CompraTrait;

/**
 * Class MinutaAlteracaoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MinutaAlteracaoCrudController extends CrudController
{
    use Formatador;
    use CompraTrait;

    public function __construct(\Yajra\DataTables\Html\Builder $htmlBuilder)
    {
        // call Grandpa's constructor
        parent::__construct();

        $this->htmlBuilder = $htmlBuilder;
        backpack_auth()->check();
    }

    public function setup()
    {

        $minuta_id = $this->crud->getCurrentEntryId();
//        $minuta_id = \Route::current()->parameter('contrato_id');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MinutaEmpenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/minuta/' . $minuta_id . '/alteracao');
        $this->crud->setEntityNameStrings('Alteração Minuta Empenho', 'Alteração Minuta Empenho');
        $this->crud->setEditView('vendor.backpack.crud.empenho.edit');
        $this->crud->setShowView('vendor.backpack.crud.empenho.show');
//        $this->crud->addButtonFromView('top', 'create', 'createbuscacompra');
        //TODO ARRUMAR O BOTÃO UPDATE ALTERACAO MINUTA EMPENHO
        $this->crud->addButtonFromView('line', 'update', 'etapaempenho', 'end');

        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->allowAccess('clone');
        $this->crud->denyAccess('delete');
        $this->crud->addClause('select', [
            'minutaempenhos.*',
        ])->distinct();

        $this->crud->addClause(
            'join',
            'compra_item_minuta_empenho',
            'compra_item_minuta_empenho.minutaempenho_id',
            '=',
            'minutaempenhos.id'
        );

        $this->crud->addClause(
            'where',
            'minutaempenhos.id',
            '=',
            $minuta_id
        );


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->adicionaCampos($minuta_id);
        $this->adicionaColunas($minuta_id);

        // add asterisk for fields that are required in MinutaEmpenhoRequest
//        $this->crud->setRequiredFields(StoreRequest::class, 'create');
//        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


//        dd(123);
    }

    public function store(StoreRequest $request)
    {

        //dd('store alteracao', $request->all());
        $minuta_id = $request->get('minuta_id');

        $compra_item_ids = $request->compra_item_id;

        $valores = $request->valor_total;

        $remessa = CompraItemMinutaEmpenho::where('minutaempenho_id', $request->minuta_id)
            ->max('remessa');

        array_walk($valores, function (&$value, $key) use ($request, $remessa) {

            $operacao = explode('|', $request->tipo_alteracao[$key]);
            $quantidade = $request->qtd[$key];
            $valor = $this->retornaFormatoAmericano($request->valor_total[$key]);

            if ($operacao[1] === 'ANULAÇÃO') {
                $quantidade = 0 - $quantidade;
                $valor = 0 - $valor;
            } elseif ($operacao[1] === 'CANCELAMENTO') {
                $item = CompraItemMinutaEmpenho::where('compra_item_id', $request->compra_item_id[$key])
                    ->where('minutaempenho_id', $request->minuta_id)
                    ->select(DB::raw('0 - sum(quantidade) as qtd, 0 - sum(valor) as vlr'))->first();
                $quantidade = $item->qtd;
                $valor = $item->vlr;
            }

            $value = [
                'compra_item_id' => $request->compra_item_id[$key],
                'minutaempenho_id' => $request->minuta_id,
                'subelemento_id' => $request->subitem[$key],
                'operacao_id' => $operacao[0],
                'remessa' => $remessa + 1,
                'quantidade' => $quantidade,
                'valor' => $valor,
            ];
        });
        dump($valores);

//        dd($compra_item_ids);
//        $compra_item_ids = array_map(
//            function ($compra_item_ids) use ($minuta_id) {
//                //                dd($compra_item_ids);
//                $compra_item_ids['minutaempenho_id'] = $minuta_id;
//                return $compra_item_ids;
//            },
//            $compra_item_ids
//        );


        DB::beginTransaction();
        try {
            $teste = CompraItemMinutaEmpenho::insert($valores);

            foreach ($valores as $index => $valor) {
                $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $valor['compra_item_id'])
                    ->where('unidade_id', session('user_ug_id'))
                    ->first();

                $compraItemUnidade->quantidade_saldo = $this->retornaSaldoAtualizado($valor['compra_item_id'])->saldo;
                $compraItemUnidade->save();

            }




//            $modMinuta = MinutaEmpenho::find($minuta_id);
//            $modMinuta->etapa = 6;
//            $modMinuta->valor_total = $request->valor_utilizado;
//            $modMinuta->save();

            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }
        dd($teste);


//        dd($minuta_id, $compra_item_ids, $valores);
//        dd('store alteracao',$request->all());
        // your additional operations before save here
//        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
//        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        dd('up alteracao', $request->all());
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

    public function create()
    {

        $minuta_id = Route::current()->parameter('minuta_id');
//        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);

        $itens = MinutaEmpenho::join(
            'compra_item_minuta_empenho',
            'compra_item_minuta_empenho.minutaempenho_id',
            '=',
            'minutaempenhos.id'
        )
            ->join(
                'compra_items',
                'compra_items.id',
                '=',
                'compra_item_minuta_empenho.compra_item_id'
            )
            ->join(
                'compras',
                'compras.id',
                '=',
                'compra_items.compra_id'
            )
            ->join(
                'codigoitens as tipo_compra',
                'tipo_compra.id',
                '=',
                'compras.tipo_compra_id'
            )
            ->join(
                'codigoitens',
                'codigoitens.id',
                '=',
                'compra_items.tipo_item_id'
            )
            ->join(
                'saldo_contabil',
                'saldo_contabil.id',
                '=',
                'minutaempenhos.saldo_contabil_id'
            )
            ->join(
                'naturezadespesa',
                'naturezadespesa.codigo',
                '=',
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6)")
            )
            ->join(
                'compra_item_fornecedor',
                'compra_item_fornecedor.compra_item_id',
                '=',
                'compra_items.id'
            )
            ->join(
                'compra_item_unidade',
                'compra_item_unidade.compra_item_id',
                '=',
                'compra_items.id'
            )
            ->where('minutaempenhos.id', $minuta_id)
            ->distinct()
            ->select(
                [
                    'compra_item_minuta_empenho.compra_item_id',
                    'compra_item_fornecedor.fornecedor_id',
                    'tipo_compra.descricao as tipo_compra_descricao',
                    'codigoitens.descricao',
                    'compra_items.catmatseritem_id',
                    'compra_items.descricaodetalhada',
                    DB::raw("SUBSTRING(compra_items.descricaodetalhada for 50) AS descricaosimplificada"),
                    'compra_item_unidade.quantidade_saldo as qtd_item',
                    'compra_item_fornecedor.valor_unitario as valorunitario',
                    'naturezadespesa.codigo as natureza_despesa',
                    'naturezadespesa.id as natureza_despesa_id',
                    'compra_item_fornecedor.valor_negociado as valortotal',
                    'saldo_contabil.saldo',
                    'compra_item_minuta_empenho.subelemento_id',
                    DB::raw("0 AS quantidade"),
                    DB::raw("0 AS valor"),
                    //                    'compra_item_minuta_empenho.quantidade',
                    //                    'compra_item_minuta_empenho.valor',
                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa")
                ]
            )
            ->get()->toArray();
//        ;dd($itens->getBindings(),$itens->toSql(),$itens->get());
//        ;dump($itens->getBindings(),$itens->toSql(),$itens->get());
//        select sum(valor) from compra_item_minuta_empenho WHERE minutaempenho_id = 8
        $valor_utilizado = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
            ->select(DB::raw('sum(valor) '))
            ->first()->toArray();
//        ;dd($valor_utilizado->getBindings(),$valor_utilizado->toSql(),$valor_utilizado->first());
//        dd($valor_utilizado);

        /*if ($request->ajax()) {
            return DataTables::of($itens)
                ->addColumn(
                    'ci_id',
                    function ($item) use ($modMinutaEmpenho) {

                        return $this->addColunaCompraItemId($item);
                    }
                )
                ->addColumn(
                    'subitem',
                    function ($item) use ($modMinutaEmpenho) {

                        return $this->addColunaSubItem($item);
                    }
                )
                ->addColumn(
                    'quantidade',
                    function ($item) {
                        return $this->addColunaQuantidade($item);
                    }
                )
                ->addColumn(
                    'valor_total',
                    function ($item) {
                        return $this->addColunaValorTotal($item);
                    }
                )
                ->addColumn(
                    'valor_total_item',
                    function ($item) {
                        return $this->addColunaValorTotalItem($item);
                    }
                )
                ->rawColumns(['subitem', 'quantidade', 'valor_total', 'valor_total_item'])
                ->make(true);
        }*/

        $html = $this->retornaGridItens($minuta_id);

//        dd($itens);

        return view(
            'backpack::mod.empenho.AlteracaoSubElemento',
            compact('html')
        )->with([
            'credito' => $itens[0]['saldo'],
            'valor_utilizado' => $valor_utilizado['sum'],
            'saldo' => $itens[0]['saldo'] - $valor_utilizado['sum'],
            'update' => false,
//            'update' => $valor_utilizado['sum'] > 0,
            'fornecedor_id' => $itens[0]['fornecedor_id'],
        ]);
    }

    public function ajax(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');
//        dd($minuta_id);
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Operação item empenho');
        })
            ->whereNotIn('descricao', ['INCLUSAO'])
//            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();

        $itens = MinutaEmpenho::join(
            'compra_item_minuta_empenho',
            'compra_item_minuta_empenho.minutaempenho_id',
            '=',
            'minutaempenhos.id'
        )
            ->join(
                'compra_items',
                'compra_items.id',
                '=',
                'compra_item_minuta_empenho.compra_item_id'
            )
            ->join(
                'compras',
                'compras.id',
                '=',
                'compra_items.compra_id'
            )
            ->join(
                'codigoitens as tipo_compra',
                'tipo_compra.id',
                '=',
                'compras.tipo_compra_id'
            )
            ->join(
                'codigoitens',
                'codigoitens.id',
                '=',
                'compra_items.tipo_item_id'
            )
            ->join(
                'saldo_contabil',
                'saldo_contabil.id',
                '=',
                'minutaempenhos.saldo_contabil_id'
            )
            ->join(
                'naturezadespesa',
                'naturezadespesa.codigo',
                '=',
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6)")
            )
            ->join(
                'compra_item_fornecedor',
                'compra_item_fornecedor.compra_item_id',
                '=',
                'compra_items.id'
            )
            ->join(
                'compra_item_unidade',
                'compra_item_unidade.compra_item_id',
                '=',
                'compra_items.id'
            )
            ->where('minutaempenhos.id', $minuta_id)
//            ->where('compra_item_unidade.quantidade_saldo', '>',0)
//            ->where('compra_item_unidade.quantidade_saldo', '>',0)
            ->distinct()
            ->select(
                [
                    'compra_item_minuta_empenho.compra_item_id',
                    'compra_item_fornecedor.fornecedor_id',
                    'tipo_compra.descricao as tipo_compra_descricao',
                    'codigoitens.descricao',
                    'compra_items.catmatseritem_id',
                    'compra_items.descricaodetalhada',
                    DB::raw("SUBSTRING(compra_items.descricaodetalhada for 50) AS descricaosimplificada"),
                    'compra_item_unidade.quantidade_saldo as qtd_item',
                    'compra_item_fornecedor.valor_unitario as valorunitario',
                    'naturezadespesa.codigo as natureza_despesa',
                    'naturezadespesa.id as natureza_despesa_id',
                    'compra_item_fornecedor.valor_negociado as valortotal',
                    'saldo_contabil.saldo',
                    'compra_item_minuta_empenho.subelemento_id',
                    //                    'compra_item_minuta_empenho.quantidade',
                    //                    'compra_item_minuta_empenho.valor',
                    DB::raw("0 AS quantidade"),
                    DB::raw("0 AS valor"),
                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa")
                ]
            )
            ->get()->toArray();
//        ;dd($itens->getBindings(),$itens->toSql(),$itens->get());
        return DataTables::of($itens)
            ->addColumn(
                'ci_id',
                function ($item) use ($modMinutaEmpenho) {
                    return $this->addColunaCompraItemId($item);
                }
            )
            ->addColumn(
                'subitem',
                function ($item) {
                    return $this->addColunaSubItem($item);
                }
            )
            ->addColumn(
                'tipo_alteracao',
                function ($item) use ($tipos) {
                    return $this->addColunaTipoAlteracao($item, $tipos);
                }
            )
            ->addColumn(
                'quantidade',
                function ($item) {
                    return $this->addColunaQuantidade($item);
                }
            )
            ->addColumn(
                'valor_total',
                function ($item) {
                    return $this->addColunaValorTotal($item);
                }
            )
            ->addColumn(
                'valor_total_item',
                function ($item) {
                    return $this->addColunaValorTotalItem($item);
                }
            )
            ->addColumn('descricaosimplificada', function ($itens) use ($modMinutaEmpenho) {
                return $this->retornaDescricaoDetalhada($itens['descricaosimplificada'], $itens['descricaodetalhada']);
            })
            ->rawColumns(['subitem', 'quantidade', 'valor_total', 'valor_total_item', 'descricaosimplificada', 'tipo_alteracao'])
//            ->rawColumns(['subitem', 'valor_total', 'valor_total_item'])
            ->make(true);
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
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    protected function adicionaCampoCipi()
    {
        $this->crud->addField([
            'name' => 'cipi',
            'label' => 'CIPI',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'disabled' => true
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
                'class' => 'form-group col-md-6'
            ]
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
            ]
        ]);
    }

    protected function adicionaCampoProcesso()
    {
        $this->crud->addField([
            'name' => 'processo',
            'label' => 'Número Processo',
            'type' => 'text',
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
        $this->adicionaColunaNumeroAnoCompra();

        $this->adicionaColunaTipoEmpenho();
        $this->adicionaColunaAmparoLegal();

        $this->adicionaColunaIncisoCompra();
        $this->adicionaColunaLeiCompra();
        $this->adicionaColunaValorTotal();

//        $this->adicionaBoxItens($minuta_id);
//        $this->adicionaBoxSaldo($minuta_id);

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

    //TODO VERIFICAR PORQUE A SITUACAO ESTÁ VINDO INATIVA PARA TODAS AS LINHAS DA MINUTA 49 NO BANCO LOCAL
    protected function adicionaColunaSituacao()
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
            'visibleInExport' => false, // notfalse important enough
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
        $itens = CompraItemMinutaEmpenho::join('compra_items', 'compra_items.id', '=', 'compra_item_minuta_empenho.compra_item_id')
            ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_item_minuta_empenho.compra_item_id')
            ->join('naturezasubitem', 'naturezasubitem.id', '=', 'compra_item_minuta_empenho.subelemento_id')
            ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
            ->join('catmatseritens', 'catmatseritens.id', '=', 'compra_items.catmatseritem_id')
            ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
//            ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_items.id')
            ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
            ->where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
            ->select([
                DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                DB::raw('fornecedores.nome AS "Fornecedor"'),
                DB::raw('codigoitens.descricao AS "Tipo do Item"'),
                DB::raw('catmatseritens.codigo_siasg AS "Código do Item"'),
                DB::raw('catmatseritens.descricao AS "Descrição"'),
                DB::raw('compra_items.descricaodetalhada AS "Descrição Detalhada"'),
                DB::raw('naturezasubitem.codigo || \' - \' || naturezasubitem.descricao AS "ND Detalhada"'),
                DB::raw('compra_item_fornecedor.valor_unitario AS "Valor unitário"'),
                DB::raw('compra_item_minuta_empenho.quantidade AS "Quantidade"'),
                DB::raw('compra_item_minuta_empenho.Valor AS "Valor Total do Item"'),


            ])
            ->get()->toArray();

        $this->crud->addColumn([
            'box' => 'itens',
            'name' => 'itens',
            'label' => 'itens', // Table column heading
//            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
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
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            'values' => $saldo
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

    /**
     * Monta $html com definições do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    private function retornaGridItens($minuta_id)
    {
//        dd(route('empenho.crud.alteracao.ajax',12));
        $rota = route('empenho.crud.alteracao.ajax', $minuta_id);

        $html = $this->htmlBuilder
            ->addColumn(
                [
                    'data' => 'ci_id',
                    'name' => 'ci_id',
                    'title' => '',
                    'orderable' => false,
                    'searchable' => false,
                    'visible' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'descricao',
                    'name' => 'descricao',
                    'title' => 'Tipo',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'catmatseritem_id',
                    'name' => 'catmatseritem_id',
                    'title' => 'Codigo',
                ]
            )
            ->addColumn(
                [
                    'data' => 'descricaosimplificada',
                    'name' => 'descricaosimplificada',
                    'title' => 'Descrição',
                ]
            )
            ->addColumn(
                [
                    'data' => 'qtd_item',
                    'name' => 'qtd_item',
                    'title' => 'Qtd. de Item',
                ]
            )
            ->addColumn(
                [
                    'data' => 'valorunitario',
                    'name' => 'valorunitario',
                    'title' => 'Valor Unit.',
                ]
            )
            ->addColumn(
                [
                    'data' => 'valor_total_item',
                    'name' => 'valor_total_item',
                    'title' => 'Valor Total do Item',
                ]
            )
            ->addColumn(
                [
                    'data' => 'natureza_despesa',
                    'name' => 'natureza_despesa',
                    'title' => 'Natureza da Despesa',
                ]
            )
            ->addColumn(
                [
                    'data' => 'subitem',
                    'name' => 'subitem',
                    'title' => 'Subelemento',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'tipo_alteracao',
                    'name' => 'tipo_alteracao',
                    'title' => 'Tipo Alteracão',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'quantidade',
                    'name' => 'quantidade',
                    'title' => 'Qtd',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'valor_total',
                    'name' => 'valor_total',
                    'title' => 'Valor Total',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->ajax([
                'url' => $rota,
                'type' => 'GET',
            ])
            ->parameters(
                [
                    'processing' => true,
                    'serverSide' => true,
                    'info' => true,
                    'order' => [
                        0,
                        'desc'
                    ],
                    'autoWidth' => false,
                    'bAutoWidth' => false,
                    'paging' => true,
                    'lengthChange' => true,
                    'language' => [
                        'url' => asset('/json/pt_br.json')
                    ],
                    'initComplete' => 'function() { $(\'.subitem\').select2(); atualizaMascara() }',
                    'responsive' => [
                        true,
                        'details' => [
                            'renderer' => '$.fn.dataTable.Responsive.renderer.listHiddenNodes()'
                        ]
                    ]
                ]
            );


        return $html;
    }

    private function addColunaSubItem($item)
    {
        $subItem = Naturezasubitem::where('naturezadespesa_id', $item['natureza_despesa_id'])
            ->where('id', $item['subelemento_id'])
            ->orderBy('codigo', 'asc')
            ->select('id', 'codigo', 'descricao')
            ->first();

        $colSubItem = " <input  type='text' class='form-control qtd' "
            . "  value='$subItem->codigo - $subItem->descricao' readonly   "
            . " title='$subItem->codigo - $subItem->descricao' >";

        $hidden = " <input  type='hidden' name='subitem[]' value='$subItem->id'>";

        return $this->addColunaCompraItemId($item) . $colSubItem . $hidden;
    }

    private function addColunaTipoAlteracao($item, $tipos)
    {

        $retorno = '<select name="tipo_alteracao[]" class="subitem" style="width:200px">';
        foreach ($tipos as $key => $value) {
//            $selected = ($key == $item['subelemento_id']) ? 'selected' : '';
//            $retorno .= "<option value='$key' $selected>$value</option>";
            $retorno .= "<option value='$key|$value' >$value</option>";
        }
        $retorno .= '</select>';
        return $retorno;
    }

    private function addColunaQuantidade($item)
    {
        $quantidade = $item['quantidade'];

        if ($item['tipo_compra_descricao'] === 'SISPP' && $item['descricao'] === 'Serviço') {
            return " <input  type='number' class='form-control qtd"
                . $item['compra_item_id'] . "' id='qtd" . $item['compra_item_id']
                . "' data-tipo='' name='qtd[]' value='$quantidade' readonly  > "
                . " <input  type='hidden' id='quantidade_total" . $item['compra_item_id']
                . "' data-tipo='' name='quantidade_total[]' value='"
                . $item['qtd_item'] . "'> ";
        }
        return " <input type='number' max='" . $item['qtd_item'] . "' min='1' id='qtd" . $item['compra_item_id']
            . "' data-compra_item_id='" . $item['compra_item_id']
            . "' data-valor_unitario='" . $item['valorunitario'] . "' name='qtd[]'"
            . " class='form-control' value='$quantidade' onchange='calculaValorTotal(this)'  > "
            . " <input  type='hidden' id='quantidade_total" . $item['compra_item_id']
            . "' data-tipo='' name='quantidade_total[]' value='" . $item['qtd_item'] . "'> ";
    }

    private function addColunaValorTotal($item)
    {
//        dd($item);
        $valor = $item['valor'];
        if ($item['tipo_compra_descricao'] === 'SISPP' && $item['descricao'] === 'Serviço') {
            return " <input  type='text' class='form-control col-md-12 valor_total vrtotal"
                . $item['compra_item_id'] . "'"
                . "id='vrtotal" . $item['compra_item_id']
                . "' data-qtd_item='" . $item['qtd_item'] . "' name='valor_total[]' value='$valor'"
                . " data-compra_item_id='" . $item['compra_item_id'] . "'"
                . " data-valor_unitario='" . $item['valorunitario'] . "'"
                . " onchange='calculaQuantidade(this)' >";
        }
        return " <input  type='text' class='form-control valor_total vrtotal" . $item['compra_item_id'] . "'"
            . "id='vrtotal" . $item['compra_item_id']
            . "' data-tipo='' name='valor_total[]' value='$valor' readonly > ";
    }

    private function addColunaValorTotalItem($item)
    {
        return "<td>" . $item['qtd_item'] * $item['valorunitario'] . "</td>"
            . " <input  type='hidden' id='valor_total_item" . $item['compra_item_id'] . "'"
            . " name='valor_total_item[]"
            . "' value='" . $item['qtd_item'] * $item['valorunitario'] . "'> ";
    }

    private function addColunaCompraItemId($item)
    {
        return " <input  type='hidden' id='" . ''
            . "' data-tipo='' name='compra_item_id[]' value='" . $item['compra_item_id'] . "'   > ";
    }
}
