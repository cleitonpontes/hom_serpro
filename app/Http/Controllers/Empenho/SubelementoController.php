<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\CompraItem;
use App\Models\MinutaEmpenho;
use App\Models\Naturezasubitem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class SubelementoController extends BaseControllerEmpenho
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(Request $request)
    {
        $etapa_id = Route::current()->parameter('etapa_id');
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        //        $fornecedor_id = \Route::current()->parameter('fornecedor_id');
//        $modMinutaEmpenho->atualizaFornecedorCompra($fornecedor_id);

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
                'minutaempenhos.saldo_contabil_id',
            )
            ->join(
                'naturezadespesa',
                'naturezadespesa.codigo',
                '=',
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6)")
            )
            ->where('minutaempenhos.id', $minuta_id)
            ->select(
                [
                    'compra_item_minuta_empenho.compra_item_id',
                    'tipo_compra.descricao as tipo_compra',
                    'codigoitens.descricao',
                    'compra_items.catmatseritem_id',
                    'compra_items.descricaodetalhada',
                    'compra_items.quantidade as qtd_item',
                    'compra_items.valorunitario',
                    'naturezadespesa.codigo as natureza_despesa',
                    'naturezadespesa.id as natureza_despesa_id',
                    'compra_items.valortotal',


                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa"),
//                    'naturezadespesa.id as naturezadespesa_id'
                ]
            )
            ->get()
            ->toArray();

//        dd($itens);
//        ;

//        dd($itens->getBindings(), $itens->toSql());

        if ($request->ajax()) {
            return DataTables::of($itens)
                ->addColumn(
                    'ci_id',
                    function ($item) use ($modMinutaEmpenho) {

                        //                    return $this->retornaRadioItens($itens['id'], $modMinutaEmpenho->id, $itens['descricao']);
                        return $this->addColunaCompraItemId($item);
                    }
                )
                ->addColumn(
                    'subitem',
                    function ($item) use ($modMinutaEmpenho) {

                        //                    return $this->retornaRadioItens($itens['id'], $modMinutaEmpenho->id, $itens['descricao']);
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
//                ->rawColumns(['ci_id','subitem', 'quantidade', 'valor_total'])
                ->rawColumns(['subitem', 'quantidade', 'valor_total'])
                ->make(true);
        }


        $html = $this->retornaGridItens();

        return view('backpack::mod.empenho.Etapa5SubElemento', compact('html'));
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
                    'data' => 'descricaodetalhada',
                    'name' => 'descricaodetalhada',
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
                    'data' => 'natureza_despesa',
                    'name' => 'natureza_despesa',
                    'title' => 'Natureza da Despesa',
                ]
            )
            ->addColumn(
                [
                    'data' => 'subitem',
                    'name' => 'subitem',
                    'title' => 'Subitem',
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
                    'responsive' => true,
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
                    'initComplete' => 'function() { $(\'.subitem\').select2() }'

                ]
            );

        return $html;
    }

//    private function retornaRadioItens($id, $minuta_id, $descricao)
    private function addColunaSubItem($item)
    {
//        dd($item);
        $subItens = Naturezasubitem::where('naturezadespesa_id', $item['natureza_despesa_id'])
            ->get()->pluck('codigo_descricao', 'id');
//        dd($subItens);

//        $retorno = '<select name="subitem[]" id="subitem" class="subitem">';
        $retorno = '<select name="item[][\'subitem\'][]" id="subitem" class="subitem">';
        foreach ($subItens as $key => $subItem) {
            $retorno .= "<option value='$key'>$subItem</option>";
        }
        $retorno .= '</select>';
        return $this->addColunaCompraItemId($item).$retorno;
//        return $retorno;
    }

    private function addColunaQuantidade($item)
    {
//        dd($item);
        if ($item['tipo_compra'] === 'SISPP' && $item['descricao'] === 'Serviço' ){
            return " <input  type='text' id='' data-tipo='' name=\"item[]['qtd'][]\" value='' disabled  > ";
        }
        return " <input  type='number' max='" . $item['qtd_item'] . "' min='1' id='qtd_" . $item['compra_item_id']
            . "' data-compra_item_id='" . $item['compra_item_id']
            . "' data-valor_unitario='" . $item['valorunitario'] . "' name=\"item[]['qtd'][]\""
            . "value='' onchange='calculaValorTotal(this)'  > ";
//        dd($item);
//        return " <input  type='text' id='' data-tipo='' name='qtd[]' value=''   > ";
    }

    private function addColunaValorTotal($item)
    {
        if ($item['tipo_compra'] === 'SISPP' && $item['descricao'] === 'Serviço' ){
            return " <input  type='text' id='vrtotal_" . $item['compra_item_id'] . "' data-tipo='' name='valor_total[]' value=''   > ";
        }
        return " <input  type='text' id='' data-tipo='' name='valor_total[]' value='' disabled  > ";
    }

    private function addColunaCompraItemId($item)
    {
//        dd($item);
        return " <input  type='hidden' id='" . '' . "' data-tipo='' name='compra_item_id[]' value='" . $item['compra_item_id'] . "'   > ";
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
