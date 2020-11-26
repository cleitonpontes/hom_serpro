<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\MinutaEmpenho;
use App\Models\Naturezasubitem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Http\Traits\Formatador;
use Alert;
use App\Http\Traits\CompraTrait;

class SubelementoController extends BaseControllerEmpenho
{
    use Formatador;
    use CompraTrait;

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);

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
                    'compra_item_minuta_empenho.quantidade',
                    'compra_item_minuta_empenho.valor',
                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa")
                ]
            )
            ->get()
            ->toArray();
//        ;dd($itens->getBindings(),$itens->toSql());
//        select sum(valor) from compra_item_minuta_empenho WHERE minutaempenho_id = 8
        $valor_utilizado = CompraItemMinutaEmpenho::where('compra_item_minuta_empenho.minutaempenho_id', $minuta_id)
            ->select(DB::raw('sum(valor) '))
            ->first()->toArray();
//        ;dd($itens->getBindings(),$itens->toSql());
//        dd($valor_utilizado);

        if ($request->ajax()) {
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
                ->addColumn('descricaosimplificada', function ($itens) use ($modMinutaEmpenho) {
                    return $this->retornaDescricaoDetalhada($itens['descricaosimplificada'], $itens['descricaodetalhada'] );
                })
                ->rawColumns(['subitem', 'quantidade', 'valor_total', 'valor_total_item','descricaosimplificada'])
                ->make(true);
        }

        $html = $this->retornaGridItens();

//        dd($itens);

        return view(
            'backpack::mod.empenho.Etapa5SubElemento',
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

    private function retornaDescricaoDetalhada($descricao, $descricaocompleta)
    {
        $retorno = '';
        $retorno .= $descricao.' <i class="fa fa-info-circle" title="'.$descricaocompleta.'"></i>';

        return $retorno;
    }

    /**
     * Monta $html com definições do Grid
     *
     * @return Builder
     */
    private function retornaGridItens()
    {

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
                    'responsive' => [true,
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
        $subItens = Naturezasubitem::where('naturezadespesa_id', $item['natureza_despesa_id'])
            ->orderBy('codigo', 'asc')
            ->get()->pluck('codigo_descricao', 'id');

        $retorno = '<select name="subitem[]" id="subitem" class="subitem" style="width:200px;">';
        foreach ($subItens as $key => $subItem) {
            $selected = ($key == $item['subelemento_id']) ? 'selected' : '';
            $retorno .= "<option value='$key' $selected>$subItem</option>";
        }
        $retorno .= '</select>';
        return $this->addColunaCompraItemId($item) . $retorno;
    }

    private function addColunaQuantidade($item)
    {
        $quantidade = $item['quantidade'];

        if ($item['tipo_compra_descricao'] === 'SISPP' && $item['descricao'] === 'Serviço') {
            return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' class='form-control qtd"
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

    public function store(Request $request)
    {
//        dump('store');
//        dump($request->all());
        $minuta_id = $request->get('minuta_id');
        if ($request->credito - $request->valor_utilizado < 0) {
            Alert::error('O saldo não pode ser negativo.')->flash();
            return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
        }
        $compra_item_ids = $request->compra_item_id;

        $valores = $request->valor_total;

        $valores = array_map(
            function ($valores) {
                return $this->retornaFormatoAmericano($valores);
            },
            $valores
        );

        if (in_array(0, $valores) || in_array('', $valores)) {
            Alert::error('O item não pode estar com valor zero.')->flash();
            return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
        }

        DB::beginTransaction();
        try {
            foreach ($compra_item_ids as $index => $item) {
//                dd($item);
                if ($valores[$index] > $request->valor_total_item[$index]) {
                    Alert::error('O valor selecionado não pode ser maior do que o valor total do item.')->flash();
                    return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
                }

                CompraItemMinutaEmpenho::where('compra_item_id', $item)
                    ->where('minutaempenho_id', $request->minuta_id)
                    ->update([
                        'subelemento_id' => $request->subitem[$index],
                        'quantidade' => ($request->qtd[$index]),
                        'valor' => $valores[$index]
                    ]);

                $compraItemUnidade = CompraItemUnidade::where('compra_item_id', $item)
                    ->where('unidade_id', session('user_ug_id'))
                    //->where('fornecedor_id', $request->fornecedor_id)
                    ->first();

                $saldo = $this->retornaSaldoAtualizado($item);
                    $compraItemUnidade->quantidade_saldo = $saldo->saldo;
                    $compraItemUnidade->save();

            }

            $modMinuta = MinutaEmpenho::find($minuta_id);
            $modMinuta->etapa = 6;
            $modMinuta->valor_total = $request->valor_utilizado;
            $modMinuta->save();

            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        return redirect()->route('empenho.crud./minuta.edit', ['minutum' => $modMinuta->id]);
    }

    public function update(Request $request)
    {
        $this->store($request);
        $minuta_id = $request->get('minuta_id');
        if ($request->credito - $request->valor_utilizado < 0) {
            Alert::error('O saldo não pode ser negativo.')->flash();
            return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
        }

        $compra_item_ids = $request->compra_item_id;

        $valores = $request->valor_total;

        $valores = array_map(
            function ($valores) {
                return $this->retornaFormatoAmericano($valores);
            },
            $valores
        );
        if (in_array(0, $valores) || in_array('', $valores)) {
            Alert::error('O item não pode estar com valor zero.')->flash();
            return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
        }

        DB::beginTransaction();
        try {
            foreach ($compra_item_ids as $index => $item) {
                if ($valores[$index] > $request->valor_total_item[$index]) {
                    Alert::error('O valor selecionado não pode ser maior do que o valor total do item.')->flash();
                    return redirect()->route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
                }

                CompraItemMinutaEmpenho::where('compra_item_id', $item)
                    ->where('minutaempenho_id', $request->minuta_id)
                    ->update([
                        'subelemento_id' => $request->subitem[$index],
                        'quantidade' => ($request->qtd[$index]),
                        'valor' => $valores[$index]
                    ]);
                CompraItem::where('id', $item)
                    ->update(['quantidade' => ($request->quantidade_total[$index] - $request->qtd[$index])]);
            }

            $modMinuta = MinutaEmpenho::find($minuta_id);
            $modMinuta->etapa = 6;
            $modMinuta->valor_total = $request->valor_utilizado;
            $modMinuta->save();

            DB::commit();
        } catch (Exception $exc) {
            DB::rollback();
        }

        return redirect()->route('empenho.crud./minuta.edit', ['minutum' => $modMinuta->id]);
    }
}
