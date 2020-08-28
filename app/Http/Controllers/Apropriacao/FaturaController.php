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
        $x = ApropriacoesFaturasContratofaturas::all();
        dd($x);


        // $f = ApropriacaoFaturas::first()->faturas()->get()->toArray();
        $f = ApropriacaoFaturas::first()->faturas()->get()->modelKeys(); //->get()->toArray();
        $g = implode(', ', $f);

        // dd($f);

        $dados = ApropriacaoFaturas::select(
            'apropriacoes_faturas.id',
            'C.numero AS contrato',
            DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS fornecedor"),
            'CF.numero AS fatura',
            'CF.ateste',
            'CF.vencimento',
            /*
            DB::raw("(
                select
                    string_agg(numero, ' / ') as faturas
                from
                    apropriacoes_faturas_contratofaturas
                where
                    visivel = true
                    and id < 20
                ) as faturas"),
            */
            'apropriacoes_faturas.valor',
            'CI.descricao AS fase',
        )
            ->join('codigoitens AS CI', 'CI.id', '=', 'apropriacoes_faturas.fase_id')
            ->join('apropriacoes_faturas_contratofaturas AS AC', 'AC.apropriacoes_faturas_id', '=', 'apropriacoes_faturas.id')
            ->join('contratofaturas AS CF', 'CF.id', '=', 'AC.contratofaturas_id')
            // ->join('contratofaturas AS CF', 'CF.id', '=', 'AC.contratofaturas_id')
            ->join('contratos AS C', 'C.id', '=', 'CF.contrato_id')
            ->join('fornecedores AS F', 'F.id', '=', 'C.fornecedor_id')
            ->orderBy('apropriacoes_faturas.id', 'desc')
        ;

        /*
        $d0 = ApropriacaoFaturas::first();
        $d1 = $d0->faturas()->get(['numero'])->toArray();
        $d01 = $d0->faturas()->pluck('numero')->toArray();
        $d1 = implode($d01, ' / ');

        $d1 = $d0->getFaturasAttribute();
        */

        /*
        $dados = Contratofatura::where('C.unidade_id', session()->get('user_ug_id'))
            ->join('contratos AS C', 'C.id', '=', 'contratofaturas.contrato_id')
            ->join('fornecedores AS F', 'F.id', '=', 'C.fornecedor_id')
            ->join('apropriacoes_faturas_contratofaturas AS AC', 'AC.contratofaturas_id', '=', 'contratofaturas.id')
            ->join('apropriacoes_faturas AS AF', 'AF.id', '=', 'AC.apropriacoes_faturas_id')
            ->select([
                'contratofaturas.id',
                'C.numero AS contrato',
                'F.cpf_cnpj_idgener',
                'F.nome',
                DB::raw("CONCAT(cpf_cnpj_idgener, ' - ', nome) AS fornecedor"),
                'contratofaturas.numero AS fatura',
                'contratofaturas.ateste',
                'contratofaturas.vencimento',
                DB::raw("'1,2,3' AS faturas"),
                'AF.valor AS valor_fatura',
                'contratofaturas.valor as vr2',
            ]);
        */

        // dd($d1, $dados->first()->toArray());
        $d1 = $dados->get()->toArray();
        dd('retornaDadosListagem', $f, $g, $d1);

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
            'title' => 'Número'
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

    private function montaHtmlAcoes($id)
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
