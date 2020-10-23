<?php

namespace App\Http\Controllers\Empenho;

use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\CompraItem;
use App\Models\MinutaEmpenho;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class SubelementoController extends BaseControllerEmpenho
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index__(Request $request)
    {
        $minuta_id = \Route::current()->parameter('minuta_id');

        $fornecedores = MinutaEmpenho::join('compras', 'compras.id', '=', 'minutaempenhos.compra_id')
            ->join('compra_items', 'compra_items.compra_id', '=', 'compras.id')
            ->join('fornecedores', 'fornecedores.id', '=', 'compra_items.fornecedor_id')
            ->distinct()
            ->select(['fornecedores.id', 'fornecedores.nome', 'fornecedores.cpf_cnpj_idgener'])
            ->get()
            ->toArray();

        if ($request->ajax()) {
            return DataTables::of($fornecedores)->addColumn('action', function ($fornecedores) use ($minuta_id) {
                return $this->retornaAcoes($fornecedores['id'], $minuta_id);
            })
                ->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.empenho.Etapa2Fornecedor', compact('html'));
    }


    public function index(Request $request)
    {
        $etapa_id = \Route::current()->parameter('etapa_id');
        $modMinutaEmpenho = MinutaEmpenho::find(\Route::current()->parameter('minuta_id'));
        $fornecedor_id = \Route::current()->parameter('fornecedor_id');
        $modMinutaEmpenho->atualizaFornecedorCompra($fornecedor_id);

        $itens = CompraItem::join('compras', 'compras.id', '=', 'compra_items.compra_id')
            ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
            ->where('compra_items.fornecedor_id', $fornecedor_id)
            ->select([
                'compra_items.id',
                'codigoitens.descricao',
                'catmatseritem_id',
                'descricaodetalhada',
                'quantidade',
                'valorunitario',
                'valortotal'])
            ->get()
            ->toArray();

        if ($request->ajax()) {
            return DataTables::of($itens)->addColumn('action', function ($itens) use ($modMinutaEmpenho) {
                return $this->retornaRadioItens($itens['id'], $modMinutaEmpenho->id, $itens['descricao']);
            })
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
    private function retornaGrid()
    {

        $html = $this->htmlBuilder
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => 'Ações',
                'orderable' => false,
                'searchable' => false
            ])
            ->addColumn([
                'data' => 'cpf_cnpj_idgener',
                'name' => 'cpf_cnpj_idgener',
                'title' => 'CNPJ - CPF - Número',
            ])
            ->addColumn([
                'data' => 'nome',
                'name' => 'nome',
                'title' => 'Fornecedor'
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
                'data' => 'action',
                'name' => 'action',
                'title' => 'Ações',
                'orderable' => false,
                'searchable' => false
            ])
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
}
