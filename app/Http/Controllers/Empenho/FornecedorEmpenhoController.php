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
use Yajra\DataTables\DataTables;


class FornecedorEmpenhoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $compra_id = \Route::current()->parameter('compra_id');
        $modCompra = Compra::find($compra_id);
        $fornecedores = $modCompra->retornaForcedoresdaCompra();

        if ($request->ajax()) {
            return DataTables::of($fornecedores)->addColumn('action', function ($fornecedores) {

                // Ações disponíveis
                $acoes = $this->retornaAcoes();

                return $acoes;
            })
//                ->editColumn('valor_bruto', '{!! number_format(floatval($valor_bruto), 2, ",", ".") !!}')
//                ->editColumn('valor_liquido', '{!! number_format(floatval($valor_liquido), 2, ",", ".") !!}')
                ->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.empenho.minutaempenho', compact('html'));
    }



    /**
     * Monta $html com definições do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    private function retornaGrid()
    {
        $html = $this->htmlBuilder->addColumn([
            'data' => 'id',
            'name' => 'id',
            'title' => 'Id',
        ])
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
     * Retorna html das ações disponíveis
     *
     * @param number $Id
     * @return string
     */
    private function retornaAcoes()
    {
        $dochabil = $this->retornaBtnSelecionar();

        $acoes = '';
        $acoes = '<div class="btn-group">';
        $acoes .= 'Selecionar';
        $acoes .= '</div>';

        return $acoes;
    }


    private function retornaBtnSelecionar()
    {
        $relatorio = '';
        $relatorio .= '<a href="/empenho/minuta/etapa2/';
        $relatorio .= '" ';
        $relatorio .= "class='btn btn-default btn-sm' ";
        $relatorio .= 'title="Selecione o fornecedor">';
        $relatorio .= '<i class="fa fa-file-o"></i></a>';

        return $relatorio;
    }

}
