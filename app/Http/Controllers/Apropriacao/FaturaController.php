<?php
/**
 * Controller com métodos e funções da Apropriação da Fatura
 *
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */

namespace App\Http\Controllers\Apropriacao;

use App\Http\Controllers\Controller;
use App\Models\ApropriacaoContratoFaturas;
use App\Models\ApropriacaoFaturas;
use App\Models\Contrato;
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
    private $contratoId = 0;
    private $msgErroFaturaDoContrato = 'Fatura não pertence ao contrato informado.';
    private $msgErroFaturaEmApropriacao = 'Fatura já apropriada.';
    private $msgErroFaturasEmApropriacao = 'Uma ou mais faturas já apropriadas.';
    private $msgErroFaturaInexistente = 'Nenhuma fatura válida foi encontrada para apropriação.';

    /**
     * Método construtor
     *
     * @param Builder $htmlBuilder
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public function __construct(Builder $htmlBuilder)
    {
        backpack_auth()->check();
        $this->htmlBuilder = $htmlBuilder;
    }

    /**
     * Apresenta o grid com a listagem das apropriações da fatura
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public function index(Request $request)
    {
        $dados = ApropriacaoFaturas::retornaDadosListagem()->get();

        if ($request->ajax()) {
            return DataTables::of($dados)
                ->addColumn('action', function ($registro) {
                    return $this->montaHtmlAcoes($registro->id);
                })
                ->editColumn('ateste', '{!!
                    !is_null($ateste) ? date_format(date_create($ateste), "d/m/Y") : ""
                !!}')
                ->editColumn('vencimento', '{!!
                    !is_null($vencimento) ? date_format(date_create($vencimento), "d/m/Y") : ""
                !!}')
                ->editColumn('total', '{!! number_format(floatval($total), 2, ",", ".") !!}')
                ->editColumn('valor', '{!! number_format(floatval($valor), 2, ",", ".") !!}')
                ->make(true);
        }

        $html = $this->retornaHtmlGrid();
        return view('backpack::mod.apropriacao.fatura', compact('html'));
    }

    /**
     * Método para criação de registro de apropriação com única fatura
     *
     * @param Contrato $contrato
     * @param Contratofatura $fatura
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public function create(Contrato $contrato, Contratofatura $fatura)
    {
        $this->contratoId = $contrato->id;
        $faturaIds = (array) $fatura->id;

        if ($this->validaFaturaDoContrato($fatura->contrato->id)) {
            \Alert::warning($this->msgErroFaturaDoContrato)->flash();
            return redirect("/gescon/meus-contratos/$this->contratoId/faturas");
        }

        if ($this->validaNaoApropriacaoDeFaturas($faturaIds)) {
            \Alert::warning($this->msgErroFaturaEmApropriacao)->flash();
            return redirect("/gescon/meus-contratos/$this->contratoId/faturas");
        }

        if ($this->validaExistenciaFaturas($faturaIds)) {
            \Alert::warning($this->msgErroFaturaInexistente)->flash();
            return redirect("/gescon/meus-contratos/$this->contratoId/faturas");
        }

        $this->gerarApropriacaoFaturas($faturaIds);

        \Alert::success('Fatura(s) incluída(s) na apropriação')->flash();
        return redirect()->route('apropriacao.faturas');
    }

    /**
     * Método para criação de registro de apropriação com uma ou mais faturas
     *
     * @return array
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    public function createMany()
    {
        $retorno['tipo'] = 'warning';
        $retorno['mensagem'] = '';

        $faturaIds = request()->entries;

        if ($this->validaNaoApropriacaoDeFaturas($faturaIds)) {
            $retorno['mensagem'] = $this->msgErroFaturasEmApropriacao;

            return json_encode($retorno);
        }

        if ($this->validaExistenciaFaturas($faturaIds)) {
            $retorno['mensagem'] = $this->msgErroFaturaInexistente;

            return json_encode($retorno);
        }

        $this->gerarApropriacaoFaturas($faturaIds);

        $retorno['tipo'] = 'success';
        return json_encode($retorno);
    }

    /**
     * Valida se fatura pertence ao contrato informado
     *
     * @param integer $faturaId
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function validaFaturaDoContrato($faturaId)
    {
        return $this->contratoId != $faturaId;
    }

    /**
     * Valida se fatura está presente em alguma apropriação que não cancelada
     *
     * @param array $faturaIds
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function validaNaoApropriacaoDeFaturas($faturaIds)
    {
        return ApropriacaoContratoFaturas::existeFatura($faturaIds);
    }

    /**
     * Valida se fatura existe
     *
     * @param array $faturaIds
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function validaExistenciaFaturas($faturaIds)
    {
        return Contratofatura::whereIn('id', $faturaIds)->doesntExist();
    }

    /**
     * Cria registro da apropriação
     *
     * @param array $faturaIds
     * @return bool
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
    protected function gerarApropriacaoFaturas($faturaIds)
    {
        $valorTotal = Contratofatura::whereIn('id', $faturaIds)->sum('valorliquido');

        DB::transaction(function () use ($valorTotal, $faturaIds) {
            $apropriacao = ApropriacaoFaturas::create([
                'valor' => $valorTotal,
                'fase_id' => 1
            ]);

            foreach ($faturaIds as $id) {
                ApropriacaoContratoFaturas::create([
                    'apropriacoes_faturas_id' => $apropriacao->id,
                    'contratofaturas_id' => $id
                ]);
            }
        });

        return true;
    }













    /**
     * Monta $html com definições para montagem do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
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
            'title' => 'Dt. Ateste'
        ]);

        $html->addColumn([
            'data' => 'vencimento',
            'name' => 'vencimento',
            'title' => 'Dt. Vencimento'
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
            'class' => 'text-nowrap',
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














    /**
     * Monta $html com definições da coluna Ações
     *
     * @param integer $id
     * @return string
     * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
     */
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

        return '<p> ações </p>';

        return $acoes;
    }
}
