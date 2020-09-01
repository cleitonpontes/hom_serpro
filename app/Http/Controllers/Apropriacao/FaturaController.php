<?php
/**
 * Controller com métodos e funções da Apropriação da Fatura
 *
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Apropriacao;

use App\Http\Controllers\Controller;
use App\Models\ApropriacaoFaturas;
use App\Models\ApropriacoesFaturasContratofaturas;
use App\Models\Contratofatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

/**
 * Disponibiliza as funcionalidades básicas para controllers
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Fatura
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @license GNU General Public License v2.0. <https://choosealicense.com/licenses/gpl-2.0/>
 */
class FaturaController extends Controller
{

    private $htmlBuilder = '';

    /**
     * Método construtor
     *
     * @param Builder $htmlBuilder
     */
    public function __construct(Builder $htmlBuilder)
    {
        // // // $this->middleware('auth');
        // // $this->middleware('web');
        // dd(backpack_auth()->check(), backpack_auth());
        backpack_auth()->check();
        $this->htmlBuilder = $htmlBuilder;
    }

    /**
     * Apresenta o grid com a listagem das apropriações da fatura
     *
     * @param Request $request
     * @return \Illuminate\View
     */
    public function index(Request $request)
    {
        // $dados = ApropriacaoFaturas::retornaDadosListagem();
        $dados = $this->retornaDadosListagem();

        if ($request->ajax()) {
            return DataTables::of($dados)
                ->addColumn('action', function ($registro) {
                    return $this->montaHtmlAcoes($registro->id);
                })
                ->editColumn('valor', '{!! number_format(floatval($valor), 2, ",", ".") !!}')
                ->make(true);
        }

        $html = $this->retornaHtmlGrid();
        return view('backpack::mod.apropriacao.fatura', compact('html'));
    }

    private function retornaDadosListagem()
    {
        $dados = ApropriacaoFaturas::retornaDadosListagem();
        // dd($dados->get()->toArray());

        return $dados->get(); //->toArray();
    }

    /**
     * Monta $html com definições para montagem do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    private function retornaHtmlGrid()
    {
        $html = $this->htmlBuilder;

        $html->addColumn([
            'data' => 'numero',
            'name' => 'numero',
            'title' => 'Contrato'
        ]);

        $html->addColumn([
            'data' => 'fornecedor',
            'name' => 'fornecedor',
            'title' => 'Fornecedor'
        ]);

        $html->addColumn([
            'data' => 'ateste',
            'name' => 'ateste',
            'title' => 'Data Ateste'
        ]);

        $html->addColumn([
            'data' => 'vencimento',
            'name' => 'vencimento',
            'title' => 'Data Vencimento'
        ]);

        $html->addColumn([
            'data' => 'faturas',
            'name' => 'faturas',
            'title' => 'Faturas'
        ]);

        $html->addColumn([
            'data' => 'total',
            'name' => 'total',
            'title' => 'Total',
            'class' => 'text-right'
        ]);

        $html->addColumn([
            'data' => 'valor',
            'name' => 'valor',
            'title' => 'Valor informado',
            'class' => 'text-right'
        ]);

        $html->addColumn([
            'data' => 'fase',
            'name' => 'fase',
            'title' => 'Fase'
        ]);

        $html->addColumn([
            'data' => 'action',
            'name' => 'action',
            'title' => 'Ações',
            'orderable' => false,
            'searchable' => false
        ]);

        $html->parameters([
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

    private function montaHtmlAcoes($id)
    {
        $conferencia = 'C';
        $editar = 'E';
        $excluir = 'X';

        // <a href="http://conta.sc/gescon/meus-contratos/2961/faturas/1052"
        //    class="btn btn-xs btn-default"
        //    title="Visualizar">
        // <i class="fa fa-eye"></i>
        // </a>

        $acoes = '';
        // $acoes .= '<div class="btn-group text-nowrap">';

        $acoes .= "[$id] ";

        $acoes .= "<a href='#' ";
        $acoes .= "   class='btn btn-xs btn-default' ";
        $acoes .= "   alt='Ação' ";
        $acoes .= "   title='Ação' ";
        $acoes .= ">";
        $acoes .= "    <i class='fa fa-play'></i> ";
        $acoes .= "</a> ";

        $acoes .= "<a href='#' ";
        $acoes .= "   class='btn btn-xs btn-default' ";
        $acoes .= "   alt='Ação' ";
        $acoes .= "   title='Ação' ";
        $acoes .= ">";
        $acoes .= "    <i class='fa fa-play'></i> ";
        $acoes .= "</a> ";

        $acoes .= "<a href='#' ";
        $acoes .= "   class='btn btn-xs btn-default' ";
        $acoes .= "   alt='Ação' ";
        $acoes .= "   title='Ação' ";
        $acoes .= ">";
        $acoes .= "    <i class='fa fa-play'></i> ";
        $acoes .= "</a> ";

        // $acoes .= '</div>';

        return $acoes;
    }
}
