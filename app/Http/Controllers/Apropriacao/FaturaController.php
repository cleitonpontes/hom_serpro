<?php
/**
 * Controller com métodos e funções da Apropriação da Fatura
 *
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Apropriacao;

use App\Http\Controllers\Controller;
use App\Models\Contratofatura;
use Illuminate\Http\Request;
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
        $this->htmlBuilder = $htmlBuilder;
        // backpack_auth()->check();
    }

    /**
     * Apresenta o grid com a listagem das apropriações da fatura
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dados = $this->retornaDadosListagem();

        if ($request->ajax()) {
            return DataTables::of($dados)
                ->addColumn('action', function ($registro) {
                    return $this->montaHtmlAcoes($registro->id);
                })
                ->make(true);
        }

        $html = $this->retornaHtmlGrid();
        return view('backpack::mod.apropriacao.fatura', compact('html'));
    }

    private function retornaDadosListagem()
    {
        $dados = Contratofatura::where('C.unidade_id', session()->get('user_ug_id'))
            ->join('contratos AS C', 'C.id', '=', 'contratofaturas.contrato_id')
            ->join('apropriacoes_faturas_contratofaturas AS AC', 'AC.contratofaturas_id', '=', 'contratofaturas.id')
            ->join('apropriacoes_faturas AS AF', 'AF.id', '=', 'AC.apropriacoes_faturas_id')
            ->select([
                'contratofaturas.id',
                'contratofaturas.numero',
                'AF.competencia',
                'AF.observacoes',
                'AF.valor',
                'contratofaturas.valor',
            ]);

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
            'data' => 'id',
            'name' => 'id',
            'title' => 'Id'
        ]);

        $html->addColumn([
            'data' => 'numero',
            'name' => 'numero',
            'title' => 'Número'
        ]);

        $html->addColumn([
            'data' => 'competencia',
            'name' => 'competencia',
            'title' => 'Competência'
        ]);

        $html->addColumn([
            'data' => 'observacoes',
            'name' => 'observacoes',
            'title' => 'Observações'
        ]);

        $html->addColumn([
            'data' => 'valor',
            'name' => 'valor',
            'title' => 'Valor',
            'class' => 'text-right'
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

    public function montaHtmlAcoes($id)
    {
        $conferencia = 'C';
        $editar = 'E';
        $excluir = 'X';

        $acoes = '';
        $acoes .= '<div class="btn-group">';
        $acoes .= " [$id] $conferencia / $editar / $excluir <i class='fa fa-play'></i></a> ";
        $acoes .= '</div>';

        return $acoes;
    }
}
