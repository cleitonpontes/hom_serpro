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
    private $msgErroFaturaEmApropriacao = 'Fatura já foi, ou está sendo, apropriada.';
    private $msgErroFaturaInexistente = 'Nenhuma fatura válida foi encontrada para apropriação.';

    /**
     * Método construtor
     *
     * @param Builder $htmlBuilder
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
     * @return \Illuminate\View
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

    public function create(Contrato $contrato, Contratofatura $fatura)
    {
        $this->contratoId = $contrato->id;
        $faturaIds = (array) $fatura->id;

        if ($this->validaFaturaDoContrato($fatura->contrato->id)) {
            \Alert::warning('Fatura não pertence ao contrato informado.')->flash();
            return redirect("/gescon/meus-contratos/$this->contratoId/faturas");
        }

        if ($this->validaNaoApropriacaoDeFaturas($faturaIds)) {
            \Alert::warning('Fatura já foi, ou está sendo, apropriada.')->flash();
            return redirect("/gescon/meus-contratos/$this->contratoId/faturas");
        }

        if ($this->validaExistenciaFaturas($faturaIds)) {
            \Alert::warning('Nenhuma fatura válida foi encontrada para apropriação.')->flash();
            return redirect("/gescon/meus-contratos/$this->contratoId/faturas");
        }

        $this->gerarApropriacaoFaturas($faturaIds);

        \Alert::success('Fatura(s) incluída(s) na apropriação')->flash();
        return redirect()->route('apropriacao.faturas');
    }










    public function createMany()
    {
        // $retorno['success'] = false;
        $retorno['status'] = 'error';
        $retorno['message'] = '';

        $faturaIds = request()->entries;

        if ($this->validaNaoApropriacaoDeFaturas($faturaIds)) {
            $retorno['message'] = 'Fatura já foi, ou está sendo, apropriada.';

            return json_encode($retorno);
        }

        if ($this->validaExistenciaFaturas($faturaIds)) {
            $retorno['message'] = 'Nenhuma fatura válida foi encontrada para apropriação.';

            return json_encode($retorno);
        }







        // $this->gerarApropriacaoFaturas($faturaIds);
        return json_encode($retorno);


        return json_encode(true);
        return json_encode(true);
    }





    

    protected function validaFaturaDoContrato($faturaId)
    {
        return $this->contratoId != $faturaId;
    }

    protected function validaNaoApropriacaoDeFaturas($faturaIds)
    {
        return ApropriacaoContratoFaturas::existeFatura($faturaIds);
    }

    protected function validaExistenciaFaturas($faturaIds)
    {
        return Contratofatura::whereIn('id', $faturaIds)->doesntExist();
    }

    protected function gerarApropriacaoFaturas($ids)
    {
        $valorTotal = Contratofatura::whereIn('id', $ids)->sum('valorliquido');

        DB::transaction(function () use ($valorTotal, $ids) {
            $apropriacao = ApropriacaoFaturas::create([
                'valor' => $valorTotal,
                'fase_id' => 1
            ]);

            foreach ($ids as $id) {
                $link = ApropriacaoContratoFaturas::create([
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
