<?php
/**
 * Controller com métodos e funções da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Empenho;

use Alert;
use App\Http\Controllers\Empenho\Minuta\BaseControllerEmpenho;
use App\Models\CompraItem;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\MinutaEmpenho;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class FornecedorEmpenhoController extends BaseControllerEmpenho
{
    public function index(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');

        $fornecedores = MinutaEmpenho::join('compras', 'compras.id', '=', 'minutaempenhos.compra_id')
            ->join('compra_items', 'compra_items.compra_id', '=', 'compras.id')
            ->join('fornecedores', 'fornecedores.id', '=', 'compra_items.fornecedor_id')
            ->distinct()
            ->where('minutaempenhos.id', $minuta_id)
            ->where('compra_items.quantidade', '>', 0)
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

    /**
     * Retorna html das ações disponíveis
     *
     * @param number $id
     * @return string
     */
    private function retornaAcoes($id, $minuta_id)
    {
        $acoes = '';
        $acoes .= '<a href="' . route('empenho.minuta.etapa.item', ['minuta_id' => $minuta_id, 'fornecedor_id' => $id]);
        $acoes .= '"Selecionar ';
        $acoes .= "class='btn btn-default btn-sm' ";
        $acoes .= 'title="Selecionar este fornecedor">';
        $acoes .= '<i class="fa fa-check-circle"></i></a>';

        return $acoes;
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

    public function item(Request $request)
    {
        $minuta_id = Route::current()->parameter('minuta_id');
        $modMinutaEmpenho = MinutaEmpenho::find($minuta_id);
        $fornecedor_id = Route::current()->parameter('fornecedor_id');
        if (!is_null($modMinutaEmpenho)) {
            $modMinutaEmpenho->atualizaFornecedorCompra($fornecedor_id);
            session(['fornecedor_compra' => $fornecedor_id]);
        }


        $itens = CompraItem::join('compras', 'compras.id', '=', 'compra_items.compra_id')
            ->join('codigoitens', 'codigoitens.id', '=', 'compra_items.tipo_item_id')
            ->where('compra_items.fornecedor_id', $fornecedor_id)
            ->where('compra_items.quantidade', '>', 0)
            ->select([
                'compra_items.id',
                'codigoitens.descricao',
                'catmatseritem_id',
                'descricaodetalhada',
                'quantidade',
                'valorunitario',
                'valortotal',
                'numero'
            ])
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

    private function retornaRadioItens($id, $minuta_id, $descricao)
    {
        $retorno = '';
        $retorno .= " <input  type='checkbox' id='$id' data-tipo='$descricao' "
            . "name='itens[][compra_item_id]' value='$id'  onclick=\"bloqueia('$descricao')\" > ";

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
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => 'Ações',
                'orderable' => false,
                'searchable' => false
            ])
            ->addColumn([
                'data' => 'numero',
                'name' => 'numero',
                'title' => 'N. Item',
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

    public function store(Request $request)
    {
        $minuta = MinutaEmpenho::find($request->minuta_id);

        $minuta_id = $request->minuta_id;
        $fornecedor_id = $request->fornecedor_id;
        $itens = $request->itens;

        if (!isset($itens)) {
            Alert::error('Escolha pelo menos 1 item da compra.')->flash();
            return redirect()->route(
                'empenho.minuta.etapa.item',
                ['minuta_id' => $minuta_id, 'fornecedor_id' => $fornecedor_id]
            );
        }

        $itens = array_map(
            function ($itens) use ($minuta_id) {
                $itens['minutaempenho_id'] = $minuta_id;
                return $itens;
            },
            $itens
        );


        DB::beginTransaction();
        try {
            CompraItemMinutaEmpenho::insert($itens);
            $minuta->etapa = 4;
            $minuta->save();
            DB::commit();

            return redirect()->route('empenho.minuta.gravar.saldocontabil', ['minuta_id' => $minuta_id]);
        } catch (Exception $exc) {
            DB::rollback();
            dd($exc);
        }
    }

    private function retornaItensAcoes($id, $minuta_id)
    {
        $acoes = '';
        $acoes .= $this->retornaRadioItens($id);

        return $acoes;
    }
}
