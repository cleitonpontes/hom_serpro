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
                'codigoitens',
                'codigoitens.id',
                '=',
                'compra_items.tipo_item_id'
            )
            //todo VERIFICAR SE É PARA BUSCAR A NATUREZADESPESA NA TELA ANTERIOR OU A PARTIR DO JOIN COM OS ITENS ( catmatseritens )
            ->join(
                'catmatseritens',
                'catmatseritens.id',
                '=',
                'compra_items.catmatseritem_id'
            )
            ->join(
                'naturezadespesa',
                DB::raw('naturezadespesa.codigo::BIGINT'),
                '=',
                'catmatseritens.codigo_siasg'
            )
            ->where('minutaempenhos.id', $minuta_id)
            ->select(
                [
                    'compra_item_minuta_empenho.compra_item_id',
                    'codigoitens.descricao',
                    'catmatseritem_id',
                    'descricaodetalhada',
                    'compra_items.quantidade as qtd_item',
                    'valorunitario',
                    'codigo',
                    'naturezadespesa.id as naturezadespesa_id']
            )
            ->get()
            ->toArray();

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
                        return $this->addColunaQuantidade();
                    }
                )
                ->addColumn(
                    'valor_total',
                    function ($item) {
                        return $this->addColunaValorTotal();
                    }
                )
                ->rawColumns(['ci_id','subitem', 'quantidade', 'valor_total'])
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
                    'data' => 'codigo',
                    'name' => 'codigo',
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

        $subItens = Naturezasubitem::where('naturezadespesa_id', $item['naturezadespesa_id'])
            ->get()->pluck('codigo_descricao', 'id');

        $retorno = '<select name="subitem[]" id="subitem" class="subitem">';
//        $retorno = '<select name="item[][\'subitem\']" id="subitem" class="subitem">';
        foreach ($subItens as $key => $subItem) {
            $retorno .= "<option value='$key'>$subItem</option>";
        }
        $retorno .= '</select>';
        return $this->addColunaCompraItemId($item).$retorno;
    }

    private function addColunaQuantidade()
    {
//        return " <input  type='text' id='' data-tipo='' name=\"item[]['qtd']\" value=''   > ";
        return " <input  type='text' id='' data-tipo='' name='qtd[]' value=''   > ";
    }

    private function addColunaValorTotal()
    {
//        return " <input  type='text' id='' data-tipo='' name=\"item[]['valor_total']\" value=''   > ";
        return " <input  type='text' id='' data-tipo='' name='valor_total[]' value=''   > ";
    }

    private function addColunaCompraItemId($item)
    {
//        return " <input  type='hidden' id='' data-tipo='' name=\"item[]['compra_item_id']\" value='" . $item['compra_item_id'] . "'   > ";
        return " <input  type='hidden' id='' data-tipo='' name='compra_item_id[]' value='" . $item['compra_item_id'] . "'   > ";
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
