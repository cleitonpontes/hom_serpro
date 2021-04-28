<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Requests\MinutaAlteracaoRequest as StoreRequest;
use App\Http\Requests\MinutaAlteracaoRequest as UpdateRequest;
use App\Http\Traits\BuscaCodigoItens;
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
use App\Models\Naturezasubitem;
use App\Models\SaldoContabil;
use App\Models\SfOrcEmpenhoDados;
use App\Repositories\Base;
use App\XML\Execsiafi;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Route;
use Alert;
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
    use BuscaCodigoItens;

    public function __construct(\Yajra\DataTables\Html\Builder $htmlBuilder)
    {
        // call Grandpa's constructor
        parent::__construct();

        $this->htmlBuilder = $htmlBuilder;
        backpack_auth()->check();
    }

    public function setup()
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $this->remessa = Route::current()->parameter('remessa');
        $minuta = MinutaEmpenho::find($minuta_id);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MinutaEmpenho');
        $this->crud->setRoute(config('backpack.base.route_prefix') . 'empenho/minuta/' . $minuta_id . '/alteracao');
        $this->crud->setEntityNameStrings('Alteração do Empenho', 'Alteração do Empenho');
        $this->crud->setEditView('vendor.backpack.crud.empenho.alteracao_edit');
        $this->crud->setShowView('vendor.backpack.crud.empenho.alteracao_show');
//        $this->crud->addButtonFromView('line', 'update', 'etapaempenho', 'end');

        $this->crud->addButtonFromView('line', 'show', 'show_alteracao', 'beginning');
        $this->crud->addButtonFromView('line', 'atualizarsituacaominuta', 'atualizarsituacaominutaalt', 'beginning');
        $this->crud->addButtonFromView('line', 'update', 'etapaempenhoalteracao', 'end');
        $this->crud->addButtonFromView('line', 'deletarminuta', 'deletarminutaalt', 'end');

        $this->crud->allowAccess('update');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess('delete');


        if ($minuta->empenho_por === 'Compra' || $minuta->empenho_por === 'Suprimento') {
            $this->crud->addClause('select', [
                'minutaempenhos.*',
                'compra_item_minuta_empenho.minutaempenhos_remessa_id',
                'minutaempenhos_remessa.etapa',
                'minutaempenhos_remessa.mensagem_siafi as mensagem_siafi_remessa',
                DB::raw('codigoitens.descricao as situacao_remessa'),
                'conta_corrente_passivo_anterior.conta_corrente',

            ])->distinct();

            $this->crud->addClause(
                'join',
                'compra_item_minuta_empenho',
                'compra_item_minuta_empenho.minutaempenho_id',
                '=',
                'minutaempenhos.id'
            );

            $this->crud->addClause(
                'join',
                'minutaempenhos_remessa',
                'minutaempenhos_remessa.id',
                '=',
                'compra_item_minuta_empenho.minutaempenhos_remessa_id'
            );
        }

        if ($minuta->empenho_por === 'Contrato') {
            $this->crud->addClause('select', [
                'minutaempenhos.*',
                'contrato_item_minuta_empenho.minutaempenhos_remessa_id',
                'minutaempenhos_remessa.etapa',
                'minutaempenhos_remessa.mensagem_siafi as mensagem_siafi_remessa',
                DB::raw('codigoitens.descricao as situacao_remessa'),
                'conta_corrente_passivo_anterior.conta_corrente',

            ])->distinct();

            $this->crud->addClause(
                'join',
                'contrato_item_minuta_empenho',
                'contrato_item_minuta_empenho.minutaempenho_id',
                '=',
                'minutaempenhos.id'
            );

            $this->crud->addClause(
                'join',
                'minutaempenhos_remessa',
                'minutaempenhos_remessa.id',
                '=',
                'contrato_item_minuta_empenho.minutaempenhos_remessa_id'
            );
        }

        $this->crud->addClause(
            'join',
            'codigoitens',
            'codigoitens.id',
            '=',
            'minutaempenhos_remessa.situacao_id'
        );
        $this->crud->addClause(
            'leftJoin',
            'conta_corrente_passivo_anterior',
            'conta_corrente_passivo_anterior.minutaempenhos_remessa_id',
            '=',
            'minutaempenhos_remessa.id'
        );
        $this->crud->addClause(
            'where',
            'minutaempenhos.id',
            '=',
            $minuta_id
        );
        $this->crud->addClause(
            'where',
            'minutaempenhos_remessa.remessa',
            '<>',
            0
        );
//        dd($this->crud->query->getBindings(),$this->crud->query->toSql(),$this->crud->query->get());


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
//        dd($minuta_id);
        $this->adicionaCampos($minuta_id);
        $this->adicionaColunas($minuta_id);

        // add asterisk for fields that are required in MinutaEmpenhoRequest
//        $this->crud->setRequiredFields(StoreRequest::class, 'create');
//        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


//        dd(123);
    }

    public function store(StoreRequest $request)
    {

        $minuta_id = $request->get('minuta_id');
        $modMinuta = MinutaEmpenho::find($minuta_id);
        $tipo = $modMinuta->empenho_por;
        $valores = $request->valor_total;

        DB::beginTransaction();
        try {
            if ($tipo === 'Compra' || $tipo === 'Suprimento') {
                $remessa = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $request->minuta_id)
                    ->join(
                        'minutaempenhos_remessa',
                        'minutaempenhos_remessa.minutaempenho_id',
                        '=',
                        'compra_item_minuta_empenho.minutaempenho_id'
                    )
                    ->max('remessa');

                $minutaEmpenhoRemessa = MinutaEmpenhoRemessa::create([
                    'minutaempenho_id' => $minuta_id,
                    'situacao_id' => $this->retornaIdCodigoItem(
                        'Situações Minuta Empenho',
                        'EM ANDAMENTO'
                    ),
                    'etapa' => 1,
                    'remessa' => $remessa + 1
                ]);


                $rota = $this->setRoute($minuta_id, $minutaEmpenhoRemessa->id);

                array_walk($valores, function (&$value, $key) use ($request, $minutaEmpenhoRemessa) {

                    $operacao = explode('|', $request->tipo_alteracao[$key]);
                    $quantidade = $request->qtd[$key];
                    $valor = $this->retornaFormatoAmericano($request->valor_total[$key]);

                    if ($operacao[1] === 'ANULAÇÃO') {
                        $quantidade = 0 - $quantidade;
                        $valor = 0 - $valor;
                    }
                    if ($operacao[1] === 'CANCELAMENTO') {
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
                        'minutaempenhos_remessa_id' => $minutaEmpenhoRemessa->id,
                        'quantidade' => $quantidade,
                        'valor' => $valor,
                        'numseq' => $request->numseq[$key],
                    ];
                });

                CompraItemMinutaEmpenho::insert($valores);

                if ($tipo === 'Compra') {
                    foreach ($valores as $index => $valor) {
                        $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $valor['compra_item_id'])
                            ->where('unidade_id', session('user_ug_id'))
                            ->first();
                        $compraItemUnidade->quantidade_saldo = $this->retornaSaldoAtualizado($valor['compra_item_id'])->saldo;
                        $compraItemUnidade->save();
                    }
                }
            }
            if ($tipo === 'Contrato') {
                $remessa = ContratoItemMinutaEmpenho::where('contrato_item_minuta_empenho.minutaempenho_id', $request->minuta_id)
                    ->join(
                        'minutaempenhos_remessa',
                        'minutaempenhos_remessa.minutaempenho_id',
                        '=',
                        'contrato_item_minuta_empenho.minutaempenho_id'
                    )
                    ->max('remessa');

                $minutaEmpenhoRemessa = MinutaEmpenhoRemessa::create([
                    'minutaempenho_id' => $minuta_id,
                    'situacao_id' => $this->retornaIdCodigoItem(
                        'Situações Minuta Empenho',
                        'EM ANDAMENTO'
                    ),
                    'etapa' => 1,
                    'remessa' => $remessa + 1
                ]);

                $rota = $this->setRoute($minuta_id, $minutaEmpenhoRemessa->id);

                array_walk($valores, function (&$value, $key) use ($request, $minutaEmpenhoRemessa) {

                    $operacao = explode('|', $request->tipo_alteracao[$key]);
                    $quantidade = $request->qtd[$key];
                    $valor = $this->retornaFormatoAmericano($request->valor_total[$key]);

                    if ($operacao[1] === 'ANULAÇÃO') {
                        $quantidade = 0 - $quantidade;
                        $valor = 0 - $valor;
                    }
                    if ($operacao[1] === 'CANCELAMENTO') {
                        $item = ContratoItemMinutaEmpenho::where('contrato_item_id', $request->contrato_item_id[$key])
                            ->where('minutaempenho_id', $request->minuta_id)
                            ->select(DB::raw('0 - sum(quantidade) as qtd, 0 - sum(valor) as vlr'))->first();
                        $quantidade = $item->qtd;
                        $valor = $item->vlr;
                    }

                    $value = [
                        'contrato_item_id' => $request->contrato_item_id[$key],
                        'minutaempenho_id' => $request->minuta_id,
                        'subelemento_id' => $request->subitem[$key],
                        'operacao_id' => $operacao[0],
                        'minutaempenhos_remessa_id' => $minutaEmpenhoRemessa->id,
                        'quantidade' => $quantidade,
                        'valor' => $valor,
                        'numseq' => $request->numseq[$key],
                    ];
                });

                ContratoItemMinutaEmpenho::insert($valores);
            }
            $base = new Base();
            $minutaEmpenhoRemessa->sfnonce = $base->geraNonceSiafiEmpenho($minuta_id, $minutaEmpenhoRemessa->id);
            $minutaEmpenhoRemessa->save();

            DB::commit();
            return Redirect::to($rota);
        } catch (Exception $exc) {
            DB::rollback();
        }
    }

    /**
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $remessa_id = Route::current()->parameter('alteracao');
        $minuta_id = $request->get('minuta_id');
        $modMinuta = MinutaEmpenho::find($minuta_id);
        $tipo = $modMinuta->empenho_por;
        $rota = $this->setRoute($minuta_id, $remessa_id);

        $valores = $request->valor_total;
        DB::beginTransaction();
        //TODO  VERIFICAR SE A LÓGICA DE RECUPERAR A REMESSA AQUI NO UPDATE ESTÁ CORRETA
        try {
            if ($tipo === 'Compra') {
                foreach ($valores as $key => $value) {
                    $operacao = explode('|', $request->tipo_alteracao[$key]);
                    $quantidade = $request->qtd[$key];
                    $valor = $this->retornaFormatoAmericano($request->valor_total[$key]);

                    switch ($operacao[1]) {
                        case 'NENHUMA':
                            $quantidade = 0;
                            $valor = 0;
                            break;
                        case 'ANULAÇÃO':
                            $quantidade = 0 - $quantidade;
                            $valor = 0 - $valor;
                            break;
                        case 'CANCELAMENTO':
                            //TODO VERIFICAR SE ESTE CODIGO FUNCIONA AQUI NO UPDATE
                            //TODO COMO ESTÁ NO UPDATE ACHO Q TEM QUE SOMAR SEM OS VALORES DA REMESSA QUE ESTÁ ATUALIZANDO
                            $item = CompraItemMinutaEmpenho::where('compra_item_id', $request->compra_item_id[$key])
                                ->where('minutaempenho_id', $request->minuta_id)
                                ->select(DB::raw('0 - sum(quantidade) as qtd, 0 - sum(valor) as vlr'))->first();
                            $quantidade = $item->qtd;
                            $valor = $item->vlr;
                            break;
                    }
                    CompraItemMinutaEmpenho::where('compra_item_id', $request->compra_item_id[$key])
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->where('minutaempenhos_remessa_id', $remessa_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$key],
                            'operacao_id' => $operacao[0],
                            'quantidade' => $quantidade,
                            'valor' => $valor,
                        ]);

                    $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $request->compra_item_id[$key])
                        ->where('unidade_id', session('user_ug_id'))
                        ->first();

                    $saldo = $this->retornaSaldoAtualizado($request->compra_item_id[$key]);
                    $compraItemUnidade->quantidade_saldo = $saldo->saldo;
                    $compraItemUnidade->save();
                }

                //provavelmente é na remessa

//                $modMinuta = MinutaEmpenho::find($minuta_id);
//                $modMinuta->etapa = 6;
//                $modMinuta->valor_total = $request->valor_utilizado;
//                $modMinuta->save();
            }

            if ($tipo === 'Contrato') {
                foreach ($valores as $key => $value) {
                    $operacao = explode('|', $request->tipo_alteracao[$key]);
                    $quantidade = $request->qtd[$key];
                    $valor = $this->retornaFormatoAmericano($request->valor_total[$key]);

                    switch ($operacao[1]) {
                        case 'NENHUMA':
                            $quantidade = 0;
                            $valor = 0;
                            break;
                        case 'ANULAÇÃO':
                            $quantidade = 0 - $quantidade;
                            $valor = 0 - $valor;
                            break;
                        case 'CANCELAMENTO':
                            //TODO VERIFICAR SE ESTE CODIGO FUNCIONA AQUI NO UPDATE
                            //TODO COMO ESTÁ NO UPDATE ACHO Q TEM QUE SOMAR SEM OS VALORES DA REMESSA QUE ESTÁ ATUALIZANDO
                            $item = ContratoItemMinutaEmpenho::where('contrato_item_id', $request->contrato_item_id[$key])
                                ->where('minutaempenho_id', $request->minuta_id)
                                ->select(DB::raw('0 - sum(quantidade) as qtd, 0 - sum(valor) as vlr'))->first();
                            $quantidade = $item->qtd;
                            $valor = $item->vlr;
                            break;
                    }

                    ContratoItemMinutaEmpenho::where('contrato_item_id', $request->contrato_item_id[$key])
                        ->where('minutaempenho_id', $request->minuta_id)
                        ->where('minutaempenhos_remessa_id', $remessa_id)
                        ->update([
                            'subelemento_id' => $request->subitem[$key],
                            'operacao_id' => $operacao[0],
                            'quantidade' => $quantidade,
                            'valor' => $valor,
                        ]);
                }
            }

            $modRemessa = MinutaEmpenhoRemessa::find($remessa_id);
            $modRemessa->etapa = 1;
            $modRemessa->save();

            DB::commit();
            return Redirect::to($rota);
        } catch (Exception $exc) {
            DB::rollback();
        }
    }

    public function show($id)
    {
        $content = parent::show($id);
        $params = Route::current()->parameters();

        $this->adicionaBoxItens($id, $params['remessa']);
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

        $this->adicionaColunaSituacaoShow($params['remessa']);

        return $content;
    }

    public function create()
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $codigoitem = Codigoitem::find($modMinutaEmpenho->tipo_empenhopor_id);
        $remessa_id = (Route::current()->parameter('remessa') ?? false);
        $valor_utilizado['sum'] = 0;

        if ($codigoitem->descricao == 'Contrato') {
            $tipo = 'contrato_item_id';

            $itens = $this->getItens($modMinutaEmpenho);

            if ($remessa_id) {
                $valor_utilizado = ContratoItemMinutaEmpenho::where(
                    'contrato_item_minuta_empenho.minutaempenho_id',
                    $minuta_id
                );
                $valor_utilizado = $valor_utilizado->where('contrato_item_minuta_empenho.minutaempenhos_remessa_id', '=', $remessa_id);
                $valor_utilizado = $valor_utilizado->select(DB::raw('coalesce(sum(valor),0) as sum'))
                    ->first()->toArray();
            }
        }
        if ($codigoitem->descricao == 'Compra') {
            $tipo = 'compra_item_id';
            $itens = $this->getItens($modMinutaEmpenho);

            if ($remessa_id) {
                $valor_utilizado = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id);
                $valor_utilizado = $valor_utilizado->where('compra_item_minuta_empenho.minutaempenhos_remessa_id', '=', $remessa_id);
                $valor_utilizado = $valor_utilizado->select(DB::raw('coalesce(sum(valor),0) as sum'))
                    ->first()->toArray();
            }
        }
        if ($codigoitem->descricao == 'Suprimento') {
            $tipo = 'compra_item_id';
            $itens = $this->getItens($modMinutaEmpenho);

            if ($remessa_id) {
                $valor_utilizado = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id);
                $valor_utilizado = $valor_utilizado->where('compra_item_minuta_empenho.minutaempenhos_remessa_id', '=', $remessa_id);
                $valor_utilizado = $valor_utilizado->select(DB::raw('coalesce(sum(valor),0) as sum'))
                    ->first()->toArray();
            }
        }

        $html = $this->retornaGridItens($minuta_id);

        $this->crud->urlVoltar = route(
            'empenho.crud./minuta.edit',
            ['minutum' => $minuta_id]
        );

        $update = strpos(Route::current()->uri, 'edit');

        $sispp_servico = (int)($itens[0]['tipo_compra_descricao'] === 'SISPP' && $itens[0]['descricao'] === 'Serviço');

        return view(
            'backpack::mod.empenho.AlteracaoSubElemento',
            compact('html')
        )->with([
            'credito' => $itens[0]['saldo'],
            'valor_utilizado' => $valor_utilizado['sum'],
            'empenhado' => $valor_utilizado['sum'],
            'saldo' => $itens[0]['saldo'] - $valor_utilizado['sum'],
            'tipo' => $tipo,
            'tipo_empenho_por' => $codigoitem->descricao,
            'update' => $update,
            'fornecedor_id' => $itens[0]['fornecedor_id'] ?? '',
            'sispp_servico' => $sispp_servico,
            'tipo_item' => $itens[0]['descricao'],
            'saldo_id' => $itens[0]['saldo_id'],
            'url_form' => $update !== false
                ? "/empenho/minuta/$minuta_id/alteracao/$remessa_id"
                : "/empenho/minuta/$minuta_id/alteracao"
        ]);
    }

    public function ajax(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $itens = $this->getItens($modMinutaEmpenho);

        $arr_tipo_empenho = [
            'Contrato' => 'contrato_item_id',
            'Compra' => 'compra_item_id',
            'Suprimento' => 'compra_item_id'
        ];

        $tipo = $arr_tipo_empenho[$modMinutaEmpenho->empenho_por];

        $notIn = ['INCLUSAO'];

        $ano_sistema = (env('ANO_SIAFI_TESTE')) ? env('ANO_SIAFI_TESTE') : date('Y');

        if ($itens[0]['exercicio'] == $ano_sistema) {
            $notIn[] = 'CANCELAMENTO';
        } else {
            $notIn[] = 'REFORÇO';
            $notIn[] = 'ANULAÇÃO';
        }

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Operação item empenho');
        })
            ->whereNotIn('descricao', $notIn)
            ->orderBy('id', 'desc')
            ->pluck('descricao', 'id')
            ->toArray();

//        ;dd($itens->getBindings(),$itens->toSql(),$itens->get());

        return DataTables::of($itens)
            ->addColumn(
                'ci_id',
                function ($item) use ($tipo) {
                    return $this->addColunaCompraItemId($item, $tipo);
                }
            )
            ->addColumn(
                'subitem',
                function ($item) use ($tipo) {
                    return $this->addColunaSubItem($item, $tipo);
                }
            )
            ->addColumn(
                'tipo_alteracao',
                function ($item) use ($tipos, $tipo) {
                    return $this->addColunaTipoOperacao($item, $tipos, $tipo);
                }
            )
            ->addColumn(
                'quantidade',
                function ($item) use ($tipo, $tipos) {
                    return $this->addColunaQuantidade($item, $tipo, $tipos);
                }
            )
            ->addColumn(
                'valor_total',
                function ($item) use ($tipo, $tipos) {
                    return $this->addColunaValorTotal($item, $tipo, $tipos);
                }
            )
            ->addColumn(
                'valor_total_item',
                function ($item) use ($tipo) {
                    return $this->addColunaValorTotalItem($item, $tipo);
                }
            )
            ->addColumn('descricaosimplificada', function ($itens) use ($modMinutaEmpenho) {
                if ($itens['descricaosimplificada'] != null && $itens['descricaosimplificada'] !== 'undefined') {
                    return $this->retornaDescricaoDetalhada(
                        $itens['descricaosimplificada'],
                        $itens['descricaodetalhada']
                    );
                }
                return $this->retornaDescricaoDetalhada(
                    $itens['catmatser_desc_simplificado'],
                    $itens['catmatser_desc']
                );
            })
            ->rawColumns(['subitem', 'quantidade', 'valor_total', 'valor_total_item', 'descricaosimplificada', 'tipo_alteracao'])
            ->make(true);
    }

    protected function adicionaCampos($minuta_id)
    {
        $this->adicionaCampoNumeroEmpenho();
        $this->adicionaCampoCipi();
        $this->adicionaCampoDataEmissao();
        $this->adicionaCampoTipoEmpenho();
//        $this->adicionaCampoFornecedor();
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
        $this->adicionaColunaTipoEmpenhoPor();
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

    protected function adicionaColunaSituacao()
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'situacao_remessa',
            'label' => 'Situação', // Table column heading
            'type' => 'text',
//            'function_name' => 'getAmparoLegal', // the method in your Model
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    protected function adicionaColunaSituacaoShow(string $remessa): void
    {
        $this->crud->addColumn([
            'box' => 'resumo',
            'name' => 'situacao_remessa',
            'label' => 'Situação', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getSituacaoRemessa', // the method in your Model
            'function_parameters' => [$remessa],
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
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

    public function adicionaBoxItens($minuta_id, $remessa)
    {
        $modMinuta = MinutaEmpenho::find($minuta_id);
        $fornecedor_id = $modMinuta->fornecedor_empenho_id;
        $fornecedor_compra_id = $modMinuta->fornecedor_compra_id;

        if ($modMinuta->empenho_por === 'Compra' || $modMinuta->empenho_por === 'Suprimento') {
            $itens = CompraItemMinutaEmpenho::join('compra_items', 'compra_items.id', '=', 'compra_item_minuta_empenho.compra_item_id')
                ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_item_minuta_empenho.compra_item_id')
                ->join('naturezasubitem', 'naturezasubitem.id', '=', 'compra_item_minuta_empenho.subelemento_id')
                ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
                ->join('catmatseritens', 'catmatseritens.id', '=', 'compra_items.catmatseritem_id')
                ->join('compra_item_unidade', 'compra_item_unidade.compra_item_id', '=', 'compra_items.id')
//            ->join('compra_item_fornecedor', 'compra_item_fornecedor.compra_item_id', '=', 'compra_items.id')
                ->join('fornecedores', 'fornecedores.id', '=', 'compra_item_fornecedor.fornecedor_id')
                ->join('codigoitens as operacao', 'operacao.id', '=', 'compra_item_minuta_empenho.operacao_id')
                ->where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->where('compra_item_minuta_empenho.minutaempenhos_remessa_id', $remessa)
                ->select([
                    DB::raw('compra_item_minuta_empenho.id as cime_id'),
                    DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                    DB::raw('fornecedores.nome AS "Fornecedor"'),
                    DB::raw('codigoitens.descricao AS "Tipo do Item"'),
                    DB::raw('catmatseritens.codigo_siasg AS "Código do Item"'),
                    DB::raw('catmatseritens.descricao AS "Descrição"'),
                    DB::raw('compra_items.descricaodetalhada AS "Descrição Detalhada"'),
                    DB::raw('naturezasubitem.codigo || \' - \' || naturezasubitem.descricao AS "ND Detalhada"'),
                    DB::raw('operacao.descricao AS "Operação"'),
                    DB::raw('compra_item_fornecedor.valor_unitario AS "Valor unitário"'),
                    DB::raw('compra_item_minuta_empenho.quantidade AS "Quantidade"'),
                    DB::raw('compra_item_minuta_empenho.Valor AS "Valor Total do Item"'),
                    'compra_item_minuta_empenho.numseq'
                ])
                ->orderBy('compra_item_minuta_empenho.numseq', 'asc');
//            $itens->where('compra_item_unidade.fornecedor_id', $fornecedor_compra_id)
//                ->where('compra_item_fornecedor.fornecedor_id', $fornecedor_compra_id);

            $itens = $this->setCondicaoFornecedor(
                $modMinuta,
                $itens,
                $modMinuta->empenho_por,
                $fornecedor_id,
                $fornecedor_compra_id
            );

            $itens = $itens->distinct()->get()->toArray();
        }

        if ($modMinuta->empenho_por === 'Contrato') {
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
                ->join('codigoitens as operacao', 'operacao.id', '=', 'contrato_item_minuta_empenho.operacao_id')
                ->where('contrato_item_minuta_empenho.minutaempenho_id', $minuta_id)
                ->where('contrato_item_minuta_empenho.minutaempenhos_remessa_id', $remessa)
                ->select([
                    DB::raw('fornecedores.cpf_cnpj_idgener AS "CPF/CNPJ/IDGENER do Fornecedor"'),
                    DB::raw('fornecedores.nome AS "Fornecedor"'),
                    DB::raw('codigoitens.descricao AS "Tipo do Item"'),

                    DB::raw('catmatseritens.codigo_siasg AS "Código do Item"'),
                    DB::raw('contratoitens.numero_item_compra AS "Número do Item"'),
                    DB::raw('catmatseritens.descricao AS "Descrição"'),
                    DB::raw("CASE
                                        WHEN contratoitens.descricao_complementar != 'undefined'
                                            THEN contratoitens.descricao_complementar
                                        ELSE ''
                                    END  AS \"Descrição Detalhada\""),
                    DB::raw('operacao.descricao AS "Operação"'),
                    DB::raw('contrato_item_minuta_empenho.quantidade AS "Quantidade"'),
                    DB::raw('contrato_item_minuta_empenho.Valor AS "Valor Total do Item"'),
                ])
                ->get()->toArray();
        }

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
            'name' => 'mensagem_siafi_remessa',
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
                    'data' => 'codigo_siasg',
                    'name' => 'codigo_siasg',
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
                    'data' => 'qtd_total_item',
                    'name' => 'qtd_total_item',
                    'title' => 'Qtd. Empenhada',
                ]
            )
            ->addColumn(
                [
                    'data' => 'vlr_total_item',
                    'name' => 'vlr_total_item',
                    'title' => 'Valor Empenhado',
                ]
            )
            ->addColumn(
                [
                    'data' => 'natureza_despesa',
                    'name' => 'natureza_despesa',
                    'title' => 'ND',
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
                    'title' => 'Tipo Operacão',
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
                    'title' => 'Valor da Alteração',
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
                    'lengthMenu' => [
                        [10, 25, 50, 100, -1],
                        ['10', '25', '50', '100', 'Todos']
                    ],
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

    private function addColunaSubItem($item, $tipo)
    {
        $subItem = Naturezasubitem::where('naturezadespesa_id', $item['natureza_despesa_id'])
            ->where('id', $item['subelemento_id'])
            ->orderBy('codigo', 'asc')
            ->select('id', 'codigo', 'descricao')
            ->first();

        $colSubItem = " <input  type='text' class='form-control ' "
            . "  value='$subItem->codigo - $subItem->descricao' readonly   "
            . " title='$subItem->codigo - $subItem->descricao' >";

        $hidden = " <input  type='hidden' name='subitem[]' value='$subItem->id'>";

        return $this->addColunaCompraItemId($item, $tipo) . $colSubItem . $hidden;
    }

    private function addColunaTipoOperacao($item, $tipos, $tipo)
    {
        $retorno = '<select name="tipo_alteracao[]" id="' . $tipo . '_' . $item[$tipo] . '"
            class="subitem" style="width:200px"
            onchange="BloqueiaValorTotal(this,' . $item[$tipo] . ')"
            data-item_id="' . $item[$tipo] . '">';
        foreach ($tipos as $key => $value) {
            $selected = ($key == $item['operacao_id']) ? 'selected' : '';
            $retorno .= "<option value='$key|$value' $selected>$value</option>";
        }
        $retorno .= '</select>';
        return $retorno;
    }

    private function addColunaQuantidade($item, $tipo, $tipos)
    {
        //CASO SEJA CONTRATO
//        clock($item, $tipo, $tipos);
        if ($tipo === 'contrato_item_id') {
            return $this->setColunaContratoQuantidade($item, $tipos);
        }

        //CASO SEJA SUPRIMENTO
        if (strpos($item['catmatser_desc'], 'SUPRIMENTO') !== false) {
            return $this->setColunaSuprimentoQuantidade($item, $tipos);
        }

        //CASO SEJA COMPRA E SISRP
        if ($item['tipo_compra_descricao'] === 'SISRP') {
            $this->setColunaCompraSisrpQuantidade($item, $tipos);
        }

        //CASO SEJA COMPRA SISPP MATERIAL
        if ($item['descricao'] === 'Material') {
            return $this->setColunaCompraSisppMaterialQuantidade($item, $tipos);
        }

        //CASO SEJA COMPRA SISPP SERVIÇO
        //if ($item['descricao'] === 'Serviço') {
        return $this->setColunaCompraSisppServicoQuantidade($item, $tipos);
        //}
    }

    private function addColunaValorTotal($item, $tipo, $tipos)
    {
        //CASO SEJA CONTRATO
        if ($tipo === 'contrato_item_id') {
            return $this->setColunaContratoValorTotal($item, $tipos);
        }

        //CASO SEJA SUPRIMENTO
        if (strpos($item['catmatser_desc'], 'SUPRIMENTO') !== false) {
            return $this->setColunaSuprimentoValorTotal($item, $tipos);
        }

        //CASO SEJA COMPRA E SISRP
        if ($item['tipo_compra_descricao'] === 'SISRP') {
            return $this->setColunaCompraSisrpValorTotal($item, $tipos);
        }

        //CASO SEJA COMPRA SISPP MATERIAL
        if ($item['descricao'] === 'Material') {
            return $this->setColunaCompraSisppMaterialValorTotal($item, $tipos);
        }

        //CASO SEJA COMPRA SISPP SERVIÇO
        //if ($item['descricao'] === 'Serviço') {
        return $this->setColunaCompraSisppServicoValorTotal($item, $tipos);
        //}
    }

    private function addColunaValorTotalItem($item, $tipo)
    {
        return "<td>" . $item['qtd_item'] * $item['valorunitario'] . "</td>"
            . " <input  type='hidden' id='valor_total_item" . $item[$tipo] . "'"
            . " name='valor_total_item[]"
            . "' value='" . $item['qtd_item'] * $item['valorunitario'] . "'> "
            . "<input type='hidden' id='vlr_total_item" . $item[$tipo] . "'"
            . " name='vlr_total_item[]' value='" . $item['vlr_total_item'] . "'>";
    }

    private function addColunaCompraItemId($item, $tipo)
    {
        return " <input  type='hidden' data-tipo='' name='" . $tipo . "[]' value='" . $item[$tipo] . "'   >" .
            " <input  type='hidden' data-tipo='' name='numseq[]' value='" . $item['numseq'] . "'   >";
    }

    private function setRoute($minuta_id, $remessa_id): string
    {

        $minuta = MinutaEmpenho::where('id', $minuta_id)->select('passivo_anterior')->first();

        if ($minuta->passivo_anterior) {
            return "empenho/minuta/{$minuta_id}/alteracao/passivo-anterior/{$remessa_id}";
        }

        return "empenho/minuta/{$minuta_id}/alteracao/{$remessa_id}/show/{$minuta_id}";
    }

    private function getItens(MinutaEmpenho $minutaEmpenho): array
    {
        $tipo = $minutaEmpenho->empenho_por;
        $fornecedor_id = $minutaEmpenho->fornecedor_empenho_id;
        $fornecedor_compra_id = $minutaEmpenho->fornecedor_compra_id;

        switch ($tipo) {
            case 'Contrato':
                $itens = MinutaEmpenho::join(
                    'contrato_item_minuta_empenho',
                    'contrato_item_minuta_empenho.minutaempenho_id',
                    '=',
                    'minutaempenhos.id'
                )
                    ->join(
                        'contratoitens',
                        'contratoitens.id',
                        '=',
                        'contrato_item_minuta_empenho.contrato_item_id'
                    )
                    ->join(
                        'compras',
                        'compras.id',
                        '=',
                        'minutaempenhos.compra_id'
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
                        'contratoitens.tipo_id'
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
                        'catmatseritens',
                        'catmatseritens.id',
                        '=',
                        'contratoitens.catmatseritem_id'
                    )
                    ->join(
                        'minutaempenhos_remessa',
                        'minutaempenhos_remessa.id',
                        '=',
                        'contrato_item_minuta_empenho.minutaempenhos_remessa_id'
                    )
                    ->where('minutaempenhos.id', $minutaEmpenho->id)
                    ->where('minutaempenhos.unidade_id', session('user_ug_id'))
                    ->distinct()
                    ->select(
                        [
                            'contrato_item_minuta_empenho.contrato_item_id',
                            'contrato_item_minuta_empenho.operacao_id',
                            'tipo_compra.descricao as tipo_compra_descricao',
                            'codigoitens.descricao',
                            'saldo_contabil.id as saldo_id',
                            'catmatseritens.codigo_siasg',
                            'catmatseritens.descricao as catmatser_desc',
                            DB::raw("SUBSTRING(catmatseritens.descricao for 50) AS catmatser_desc_simplificado"),
                            'contratoitens.descricao_complementar as descricaodetalhada',
                            DB::raw("SUBSTRING(contratoitens.descricao_complementar for 50) AS descricaosimplificada"),

                            'contratoitens.quantidade as qtd_item',
                            'contratoitens.valorunitario as valorunitario',
                            'naturezadespesa.codigo as natureza_despesa',
                            'naturezadespesa.id as natureza_despesa_id',
                            'contratoitens.valortotal',
                            'saldo_contabil.saldo',
                            'contrato_item_minuta_empenho.subelemento_id',
                            DB::raw('left(minutaempenhos.mensagem_siafi, 4) as exercicio'),
                            'contrato_item_minuta_empenho.numseq'
                        ]
                    )
                    ->orderBy('contrato_item_minuta_empenho.numseq', 'asc');
                $soma = ContratoItemMinutaEmpenho::select([
                    'contrato_item_id',
                    DB::raw("sum(contrato_item_minuta_empenho.quantidade) as qtd_total_item"),
                    DB::raw("sum(contrato_item_minuta_empenho.valor) as vlr_total_item"),
                ])
                    ->where('minutaempenho_id', $minutaEmpenho->id)
                    ->groupBy('contrato_item_id');

                //CREATE
                if (is_null(session('remessa_id'))) {
                    $itens->where('minutaempenhos_remessa.remessa', 0);

                    $itens->addSelect([
                        DB::raw("0 AS quantidade"),
                        DB::raw("0 AS valor"),
                    ]);

                    return $this->retornarArray($itens->get()->toArray(), $soma->get()->toArray(), 'contrato_item_id');
                }

                //UPDATE
                $itens->where('contrato_item_minuta_empenho.minutaempenhos_remessa_id', session('remessa_id'));

                $itens->addSelect([
                    'contrato_item_minuta_empenho.quantidade',
                    'contrato_item_minuta_empenho.valor',
                ]);

                $soma->where('minutaempenhos_remessa_id', '<>', session('remessa_id'));

                return $this->retornarArray($itens->get()->toArray(), $soma->get()->toArray(), 'contrato_item_id');

                break;
            case 'Compra':
            case 'Suprimento':
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
                    ->join(
                        'catmatseritens',
                        'catmatseritens.id',
                        '=',
                        'compra_items.catmatseritem_id'
                    )
                    ->join(
                        'minutaempenhos_remessa',
                        'minutaempenhos_remessa.id',
                        '=',
                        'compra_item_minuta_empenho.minutaempenhos_remessa_id'
                    )
                    ->where('minutaempenhos.id', $minutaEmpenho->id)
                    ->where('compra_item_unidade.unidade_id', session('user_ug_id'))
                    ->distinct()
                    ->select(
                        [
                            DB::raw('compra_item_minuta_empenho.id as cime_id'),
                            'compra_item_minuta_empenho.compra_item_id',
                            'compra_item_minuta_empenho.operacao_id',
                            'compra_item_fornecedor.fornecedor_id',
                            'tipo_compra.descricao as tipo_compra_descricao',
                            'codigoitens.descricao',
                            'catmatseritens.descricao AS catmatser_desc',
                            DB::raw('SUBSTRING(catmatseritens.descricao FOR 50) AS catmatser_desc_simplificado'),
                            'compra_items.catmatseritem_id',
                            'compra_items.descricaodetalhada',
                            'catmatseritens.codigo_siasg',
                            DB::raw("SUBSTRING(compra_items.descricaodetalhada for 50) AS descricaosimplificada"),
                            'compra_item_unidade.quantidade_saldo as qtd_item',
                            'compra_item_fornecedor.valor_unitario as valorunitario',
                            'naturezadespesa.codigo as natureza_despesa',
                            'naturezadespesa.id as natureza_despesa_id',
                            'compra_item_fornecedor.valor_negociado as valortotal',
                            'saldo_contabil.saldo',
                            'saldo_contabil.id as saldo_id',
                            'compra_item_minuta_empenho.subelemento_id',
                            DB::raw('left(minutaempenhos.mensagem_siafi, 4) as exercicio'),
                            'compra_item_minuta_empenho.numseq'
                        ]
                    )
                    ->orderBy('compra_item_minuta_empenho.numseq', 'asc');
                $itens = $this->setCondicaoFornecedor(
                    $minutaEmpenho,
                    $itens,
                    $minutaEmpenho->empenho_por,
                    $fornecedor_id,
                    $fornecedor_compra_id
                );
//                $itens->where('compra_item_unidade.fornecedor_id', $fornecedor_compra_id)
//                ->where('compra_item_fornecedor.fornecedor_id', $fornecedor_compra_id);


                $soma = CompraItemMinutaEmpenho::select([
                    'compra_item_id',
                    DB::raw("sum(compra_item_minuta_empenho.quantidade) as qtd_total_item"),
                    DB::raw("sum(compra_item_minuta_empenho.valor) as vlr_total_item"),
                ])
                    ->where('minutaempenho_id', $minutaEmpenho->id)
                    ->groupBy('compra_item_id');

                //CREATE
                if (is_null(session('remessa_id'))) {
                    $itens->where('minutaempenhos_remessa.remessa', 0);
                    $itens->addSelect([
                        DB::raw("0 AS quantidade"),
                        DB::raw("0 AS valor"),
                    ]);


                    return $this->retornarArray($itens->get()->toArray(), $soma->get()->toArray(), 'compra_item_id');
                }

                //UPDATE
                $itens->addSelect([
                    'compra_item_minuta_empenho.quantidade',
                    'compra_item_minuta_empenho.valor',
                ]);
                $itens->where('compra_item_minuta_empenho.minutaempenhos_remessa_id', session('remessa_id'));

                $soma->where('minutaempenhos_remessa_id', '<>', session('remessa_id'));

                return $this->retornarArray($itens->get()->toArray(), $soma->get()->toArray(), 'compra_item_id');
                break;
        }
    }

    public function testeSaldoAtualizado($compraitem_id)
    {
        return $this->retornaSaldoAtualizado($compraitem_id);
    }

    public function executarAtualizacaoSituacaoMinuta($id, $remessa_id)
    {
        $remessa = MinutaEmpenhoRemessa::find($remessa_id);
        $date_time = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        if ($remessa->situacao->descricao == 'ERRO') {
            DB::beginTransaction();
            try {
                $situacao_id = $this->retornaIdCodigoItem('Situações Minuta Empenho', 'EM PROCESSAMENTO');
                $remessa->situacao_id = $situacao_id;
                $remessa->save();

                $modSfOrcEmpenhoDados = SfOrcEmpenhoDados::where('minutaempenhos_remessa_id', $remessa_id)
                    ->latest()
                    ->first();

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
            } catch (Exception $exc) {
                DB::rollback();
            }

            Alert::success('Situação da minuta alterada com sucesso!')->flash();
            return redirect("/empenho/minuta/$id/alteracao");
        }

        if ($remessa->situacao->descricao == 'EM PROCESSAMENTO') {

            $modSfOrcEmpenhoDados = SfOrcEmpenhoDados::where('minutaempenhos_remessa_id', $remessa_id)
                ->latest()
                ->first();

            $updated_at = \DateTime::createFromFormat('Y-m-d H:i:s', $modSfOrcEmpenhoDados->updated_at)->modify('+15 minutes');

            if ($date_time < $updated_at) {
                Alert::warning('Situação da minuta de alteração não pode ser modificada, tente novamente em 15 minutos!')->flash();
                return redirect("/empenho/minuta/$id/alteracao");
            }

            if (!$remessa->sfnonce) {
                if (!$modSfOrcEmpenhoDados->sfnonce_id) {
                    Alert::warning('Minuta de alteração com problema! Por favor crie uma nova minuta de alteração!')->flash();
                    return redirect("/empenho/minuta/$id/alteracao");
                }
                $modSfOrcEmpenhoDados->sfnonce = $modSfOrcEmpenhoDados->sfnonce_id;
            }

            $modSfOrcEmpenhoDados->txtdescricao .= ' ';
            $modSfOrcEmpenhoDados->situacao = 'EM PROCESSAMENTO';
            $modSfOrcEmpenhoDados->save();

            Alert::success('Minuta de alteração será processada novamente, por favor aguarde!')->flash();
            return redirect("/empenho/minuta/$id/alteracao");
        }

        Alert::warning('Situação da minuta de alteração não pode ser modificada!')->flash();
        return redirect("/empenho/minuta/$id/alteracao");
    }

    public function deletarMinuta($id, $remessa_id)
    {
        $minuta = MinutaEmpenho::find($id);
        $remessa = MinutaEmpenhoRemessa::find($remessa_id);
        if ($remessa->situacao->descricao == 'ERRO' || $remessa->situacao->descricao == 'EM ANDAMENTO') {
            DB::beginTransaction();
            try {
                if ($minuta->empenho_por === 'Compra') {
                    $cime = $remessa->retornaCompraItemMinutaEmpenho();
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
                    $remessa->forceDelete();
                    DB::commit();
                    Alert::success('Minuta Deletada com sucesso!')->flash();
                    return redirect($this->crud->route);
                }
                // Deletar minuta do contrato
                $remessa->forceDelete();
                DB::commit();

                Alert::success('Minuta Deletada com sucesso!')->flash();
                return redirect($this->crud->route);
            } catch (Exception $exc) {
                DB::rollback();
                Alert::error('Erro! Tente novamente mais tarde!')->flash();
                return redirect($this->crud->route);
            }
        }
        Alert::warning('Operação não permitida!')->flash();
        return redirect($this->crud->route);
    }

    public function retornarArray($return, $return_soma, $tipo)
    {
        $return = array_map(
            function ($return) use ($return_soma, $tipo) {
                $id = array_search($return[$tipo], array_column($return_soma, $tipo));
                $return['qtd_total_item'] = $return_soma[$id]['qtd_total_item'];
                $return['vlr_total_item'] = $return_soma[$id]['vlr_total_item'];
                $vlr_unitario_item = 0;
                if (($return_soma[$id]['vlr_total_item'] > 0) && ($return_soma[$id]['qtd_total_item'] > 0)) {
                    $vlr_unitario_item = round(($return_soma[$id]['vlr_total_item'] / $return_soma[$id]['qtd_total_item']), 4);
                }
                $return['vlr_unitario_item'] = $this->ceil_dec($vlr_unitario_item, 2);
                return $return;
            },
            $return
        );

        return $return;
    }

    function ceil_dec($val, $dec)
    {
        $pow = pow(10, $dec);
        return ceil(number_format($pow * $val, 2, '.', '')) / $pow;
    }
}
