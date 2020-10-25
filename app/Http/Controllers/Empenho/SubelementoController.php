<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\CompraItem;
use App\Models\MinutaEmpenho;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            ->where('minutaempenhos.id', $minuta_id)
            ->select(
                [
                    'codigoitens.descricao',
                    'catmatseritem_id',
                    'descricaodetalhada',
                    'compra_items.quantidade',
                    'valorunitario',
                    'subelemento_id']
            )
            ->get()
            ->toArray();
//        dd($itens);

        if ($request->ajax()) {
            return DataTables::of($itens)
                ->addColumn(
                    'action',
                    function ($itens) use ($modMinutaEmpenho) {

                        //                    return $this->retornaRadioItens($itens['id'], $modMinutaEmpenho->id, $itens['descricao']);
                        return $this->retornaRadioItens();
                    }
                )
                ->addColumn(
                    'operations',
                    function ($itens) {
                        return $this->addNovaColuna();
                    }
                )
                ->rawColumns(['action', 'operations'])

                ->make(true);
        }


        $html = $this->retornaGridItens();

        return view('backpack::mod.empenho.Etapa3Itensdacompra', compact('html'));
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
                    'data' => 'action',
                    'name' => 'action',
                    'title' => 'Ações',
                    'orderable' => false,
                    'searchable' => false
                ]
            )
            ->addColumn(
                [
                    'data' => 'operations',
                    'name' => 'operations',
                    'title' => 'teste',
                    'orderable' => false,
                    'searchable' => false
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
                    'data' => 'quantidade',
                    'name' => 'quantidade',
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
                    'data' => 'subelemento_id',
                    'name' => 'subelemento_id',
                    'title' => 'Subelemento',
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
                    ]
                ]
            );

        return $html;
    }

//    private function retornaRadioItens($id, $minuta_id, $descricao)
    private function retornaRadioItens()
    {
        $retorno = '';
        $retorno .= " <input  type='text' >";
//        $retorno .= " <input  type='text' id='$id' data-tipo='$descricao'" .
//            "name='itens[][compra_item_id]' value='$id'  onclick=\"bloqueia('$descricao')\" > ";

        return $retorno;
    }

    private function addNovaColuna()
    {
        return '<select name="cars" id="cars">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select>';
    }
}
