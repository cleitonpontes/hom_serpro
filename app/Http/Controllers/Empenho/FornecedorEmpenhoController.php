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
            return DataTables::of($fornecedores)->addColumn('action', function ($apropriacao) {
                // Se dada apropriação já tiver sido finalizada...
                //$finalizada = $apropriacao->fase_id == Apropriacaofases::APROP_FASE_FINALIZADA ? true : false;

                // Ações disponíveis
                $acoes = $this->retornaAcoes();

                return $acoes;
            })
                ->editColumn('valor_bruto', '{!! number_format(floatval($valor_bruto), 2, ",", ".") !!}')
                ->editColumn('valor_liquido', '{!! number_format(floatval($valor_liquido), 2, ",", ".") !!}')
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
                'data' => 'competencia',
                'name' => 'competencia',
                'title' => 'Competência'
            ])
            ->addColumn([
                'data' => 'nivel',
                'name' => 'nivel',
                'title' => 'Nível'
            ])
            ->addColumn([
                'data' => 'valor_bruto',
                'name' => 'valor_bruto',
                'title' => 'VR Bruto',
                'class' => 'text-right'
            ])
            ->addColumn([
                'data' => 'valor_liquido',
                'name' => 'valor_liquido',
                'title' => 'VR Líquido',
                'class' => 'text-right'
            ])
            ->addColumn([
                'data' => 'arquivos',
                'name' => 'arquivos',
                'title' => 'Arquivos'
            ])
            ->addColumn([
                'data' => 'fase',
                'name' => 'fase',
                'title' => 'Status'
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
     * @param number $apropriacaoId
     * @param string $finalizada
     * @return string
     */
    private function retornaAcoes()
    {
//        $editar = $this->retornaBtnEditar($apropriacaoId, $faseId);
//        $excluir = $this->retornaBtnExcluir($apropriacaoId);
//        $relatorio = $this->retornaBtnRelatorio($apropriacaoId);
//        $dochabil = $this->retornaBtnDocHabil($apropriacaoId);

//        $acaoFinalizada = $relatorio . $dochabil;
//        $acaoEmAndamento = $editar . $excluir;
//
//        if ($faseId >= Apropriacaofases::APROP_FASE_PERSISTIR_DADOS) {
//            $acaoEmAndamento .= $relatorio;
//        }

        $acoes = '';
        $acoes = '<div class="btn-group">';
        $acoes .= (true) ? $acaoFinalizada : $acaoEmAndamento;
        $acoes .= '</div>';

        return $acoes;
    }

    /**
     * Retorna html do botão editar
     *
     * @param number $apropriacaoId
     * @param string $finalizada
     * @return string
     */
    private function retornaBtnEditar($apropriacaoId, $faseId = 2)
    {
        $editar = '';
        $editar .= '<a href="/folha/apropriacao/passo/';
        $editar .= $faseId;
        $editar .= '/apid/';
        $editar .= $apropriacaoId . '" ';
        $editar .= "class='btn btn-default btn-sm' ";
        $editar .= 'title="Apropriar competência">';
        $editar .= '<i class="fa fa-play"></i></a>';

        return $editar;
    }

    /**
     * Retorna html do botão excluir
     *
     * @param number $apropriacaoId
     * @param string $finalizada
     * @return string
     */
    private function retornaBtnExcluir($apropriacaoId)
    {
        $excluir = '';
        $excluir .= '<a href="#" ';
        $excluir .= "class='btn btn-default btn-sm '";
        $excluir .= 'data-toggle="modal" ';
        $excluir .= 'data-target="#confirmaExclusaoApropriacao" ';
        $excluir .= 'data-link="/folha/apropriacao/remove/';
        $excluir .= $apropriacaoId . '" ';
        $excluir .= 'name="delete_modal" ';
        $excluir .= 'title="Excluir apropriação">';
        $excluir .= '<i class="fa fa-trash"></i></a>';

        return $excluir;
    }

    /**
     * Retorna html do botão do relatório da apropriação
     *
     * @param number $apropriacaoId
     * @return string
     */
    private function retornaBtnRelatorio($apropriacaoId)
    {
        $relatorio = '';
        $relatorio .= '<a href="/folha/apropriacao/relatorio/';
        $relatorio .= $apropriacaoId . '" ';
        $relatorio .= "class='btn btn-default btn-sm' ";
        $relatorio .= 'title="Relatório da apropriação">';
        $relatorio .= '<i class="fa fa-list-alt"></i></a>';

        return $relatorio;
    }

    private function retornaBtnDocHabil($apropriacaoId)
    {
        $relatorio = '';
        $relatorio .= '<a href="/folha/apropriacao/siafi/dochabil/';
        $relatorio .= $apropriacaoId . '" ';
        $relatorio .= "class='btn btn-default btn-sm' ";
        $relatorio .= 'title="Documento Hábil Apropriado">';
        $relatorio .= '<i class="fa fa-file-o"></i></a>';

        return $relatorio;
    }

}
