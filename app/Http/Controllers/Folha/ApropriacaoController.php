<?php
/**
 * Controller com métodos e funções da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha;

use App\Http\Controllers\Folha\Apropriacao\BaseController;
use App\Models\Apropriacao;
use App\Models\Apropriacaofases;
use App\Models\Apropriacaoimportacao;
use App\Models\SfPco;
use App\Models\SfDespesaAnular;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

/**
 * Disponibiliza as funcionalidades referentes a Apropriação da Folha
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class ApropriacaoController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelo = new Apropriacao();
        $apropriacao = $modelo->retornaListagem();

        if ($request->ajax()) {
            return DataTables::of($apropriacao)->addColumn('action', function ($apropriacao) {
                // Se dada apropriação já tiver sido finalizada...
                $finalizada = $apropriacao->fase_id == Apropriacaofases::APROP_FASE_FINALIZADA ? true : false;

                // Ações disponíveis
                $acoes = $this->retornaAcoes($apropriacao->id, $apropriacao->fase_id, $finalizada);

                return $acoes;
            })
            ->editColumn('valor_bruto', '{!! number_format(floatval($valor_bruto), 2, ",", ".") !!}')
            ->editColumn('valor_liquido', '{!! number_format(floatval($valor_liquido), 2, ",", ".") !!}')
            ->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.folha.apropriacao', compact('html'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        $apropriacao = Apropriacao::findOrFail($id);

        $msg = config('mensagens.apropriacao-exclusao-alerta');
        $status = 'Alerta';

        if ($apropriacao->fase_id != Apropriacaofases::APROP_FASE_FINALIZADA) {
            // Exclui os registros importados da apropriação
            Apropriacaoimportacao::where('apropriacao_id', $id)->delete();
            
            // Exclui o registro da própria apropriação
            $apropriacao->delete();

            $msg = config('mensagens.apropriacao-exclusao');
            $status = 'Sucesso';
        }

        $this->exibeMensagem($msg, $status);

        return redirect('/folha/apropriacao')->withInput();
    }

    /**
     * Exibe relatório da apropriação
     * 
     * @param $apid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function relatorio($apid)
    {
        $modeloApropriacao = new Apropriacao();
        $apropriacoes = $modeloApropriacao->retornaDadosRelatorio($apid);
        
        // Verificação de presença dos dados
        if (count($apropriacoes) != 1) {
            $msg = config('mensagens.apropriacao-relatorio-erro-ident');
            $this->exibeMensagemAlerta($msg);
            
            return redirect('/folha/apropriacao')->withInput();
        }
        
        // Ajusta dados, se for o caso
        $apropriacao = isset($apropriacoes[0]) ? $apropriacoes[0] : null;
        
        $processo = $apropriacao['nup'];
        
        // Verifica se já passou pelo passo 5
        if ($processo == '') {
            $msg = config('mensagens.apropriacao-relatorio-erro-passo-5');
            $this->exibeMensagemAlerta($msg);
            
            return redirect('/folha/apropriacao')->withInput();
        }
        
        $modeloPco = new SfPco();
        $pcos = $modeloPco->retornaDadosRelatorioApropriacao($apid);
        
        // Verifica se já passou pelo passo 6
        if (count($pcos) <= 0) {
            $msg = config('mensagens.apropriacao-relatorio-erro-pco');
            $this->exibeMensagemAlerta($msg);
            
            return redirect('/folha/apropriacao')->withInput();
        }
        
        $modeloDespesa = new SfDespesaAnular();
        $despesas = $modeloDespesa->retornaDadosRelatorioApropriacao($apid);
        
        return view('backpack::mod.folha.apropriacao.relatorio', compact('apid', 'apropriacao', 'pcos', 'despesas'));
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
            'title' => 'Id'
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
            'name' => 'F.fase',
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
            'autoWidth' => true,
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
    private function retornaAcoes($apropriacaoId, $faseId, $finalizada)
    {
        $editar = $this->retornaBtnEditar($apropriacaoId, $faseId);
        $excluir = $this->retornaBtnExcluir($apropriacaoId);
        $relatorio = $this->retornaBtnRelatorio($apropriacaoId);
        
        $acaoFinalizada = $relatorio;
        $acaoEmAndamento = $editar . $excluir;
        
        if ($faseId >= Apropriacaofases::APROP_FASE_PERSISTIR_DADOS) {
            $acaoEmAndamento .= $relatorio;
        }
        
        $acoes = '';
        $acoes = '<div class="btn-group">';
        $acoes .= ($finalizada == true) ? $acaoFinalizada : $acaoEmAndamento;
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
    private function retornaBtnRelatorio($apropriacaoId) {
        $relatorio = '';
        $relatorio .= '<a href="/folha/apropriacao/relatorio/';
        $relatorio .= $apropriacaoId . '" ';
        $relatorio .= "class='btn btn-default btn-sm' ";
        $relatorio .= 'title="Relatório da apropriação">';
        $relatorio .= '<i class="fa fa-list-alt"></i></a>';
        
        return $relatorio;
    }
}
