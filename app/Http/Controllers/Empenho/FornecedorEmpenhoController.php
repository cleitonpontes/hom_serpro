<?php
/**
 * Controller com métodos e funções da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Empenho;

use App\Http\Controllers\Folha\Apropriacao\BaseController;
use App\Jobs\ApropriaAlteracaoDhFolhaJob;
use App\Models\Apropriacao;
use App\Models\Apropriacaofases;
use App\Models\Apropriacaoimportacao;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Execsfsituacao;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\SfCentroCusto;
use App\Models\SfDadosBasicos;
use App\Models\SfDocOrigem;
use App\Models\SfPadrao;
use App\Models\SfPco;
use App\Models\SfDespesaAnular;
use App\Models\SfPcoItem;
use App\Models\Sfrelitemvlrcc;
use App\XML\Execsiafi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;


class FornecedorEmpenhoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');

        $fornecedores = MinutaEmpenho::join('compras', 'compras.id', '=', 'minutaempenhos.compra_id')
            ->join('compra_items', 'compra_items.compra_id', '=', 'compras.id')
            ->join('fornecedores', 'fornecedores.id', '=', 'compra_items.fornecedor_id')
            ->distinct()
            ->select(['fornecedores.id','fornecedores.nome'])
            ->get()
            ->toArray();

        if ($request->ajax()) {
            return DataTables::of($fornecedores)->addColumn('action', function ($fornecedores) use ($minuta_id) {
                $acoes = $this->retornaAcoes($fornecedores['id'], $minuta_id);

                return $acoes;
            })
                ->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.empenho.minutaempenho', compact('html'));
    }

    public function item(Request $request)
    {
        $etapa_id = Route::current()->parameter('etapa_id');
        $minuta_id = Route::current()->parameter('minuta_id');
        $fornecedor_id = Route::current()->parameter('fornecedor_id');

        $itens = CompraItem::join('compras', 'compras.id', '=', 'compra_items.compra_id')
            ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
            ->where('compra_items.fornecedor_id', $fornecedor_id)
            ->select(['codigoitens.descricao', 'catmatseritem_id', 'descricaodetalhada', 'quantidade', 'valorunitario', 'valortotal'])
            ->get()
            ->toArray();

        if ($request->ajax()) {
            return DataTables::of($itens)
//                ->editColumn('valor_bruto', '{!! number_format(floatval($valor_bruto), 2, ",", ".") !!}')
//                ->editColumn('valor_liquido', '{!! number_format(floatval($valor_liquido), 2, ",", ".") !!}')
                ->make(true);
        }


        $html = $this->retornaGridItens();

        return view('backpack::mod.empenho.minutaempenho', compact('html'));
    }


    /**
     * Monta $html com definições do Grid
     *
     * @return Builder
     */
    private function retornaGrid()
    {

        $html = $this->htmlBuilder
//            ->addColumn([
//                'data' => 'id',
//                'name' => 'id',
//                'title' => 'Id',
//            ])
            ->addColumn([
                'data' => 'nome',
                'name' => 'nome',
                'title' => 'Fornecedor'
            ])
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => 'Ações',
                'orderable' => false,
                'searchable' => false
            ])
            ->parameters([
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
            ]);

        return $html;
    }

    /**
     * Monta $html com definições do Grid
     *
     * @return Builder
     */
    private function retornaGridItens()
    {

        $html = $this->htmlBuilder
            ->addColumn([
                'data' => 'descricao',
                'name' => 'descricao',
                'title' => 'Tipo',
            ])
            ->addColumn([
                'data' => 'catmatseritem_id',
                'name' => 'catmatseritem_id',
                'title' => 'Codigo',
            ])
            ->addColumn([
                'data' => 'descricaodetalhada',
                'name' => 'descricaodetalhada',
                'title' => 'Descrição',
            ])
            ->addColumn([
                'data' => 'quantidade',
                'name' => 'quantidade',
                'title' => 'Quantidade',
            ])
            ->addColumn([
                'data' => 'valorunitario',
                'name' => 'valorunitario',
                'title' => 'Valor Unit.',
            ])
            ->addColumn([
                'data' => 'valortotal',
                'name' => 'valortotal',
                'title' => 'Valor Total.',
            ])
            ->parameters([
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
            ]);

        return $html;
    }

    /**
     * Retorna html das ações disponíveis
     *
     * @param number $id
     * @return string
     */
    private function retornaAcoes($id, $minuta_id)
    {

        $acoes = '';
        $acoes .= '<a href="/empenho/item/3/' . $minuta_id . '/' . $id;
        $acoes .= '"Selecionar ';
        $acoes .= "class='btn btn-default btn-sm' ";
        $acoes .= 'title="Selecione o fornecedor">';
        $acoes .= '<i class="fa fa-check-circle"></i></a>';

        return $acoes;
    }


    private function retornaBtnSelecionar($id)
    {
        $selecionar = '';
        $selecionar .= '<a href="/empenho/minuta/etapa2/';
        $selecionar .= '"Selecionar ';
        $selecionar .= "class='btn btn-default btn-sm' ";
        $selecionar .= 'title="Selecione o fornecedor">';
        $selecionar .= '<i class="fa fa-check-circle"></i></a>';

        return $selecionar;
    }

}
