<?php
/**
 * Controller com métodos e funções do Passo 3 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Models\Apropriacaonotaempenho;
use App\Models\Apropriacaosituacao;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

/**
 * Disponibiliza as funcionalidades específicas para o Passo 3 - Identificar Empenho
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Passo3
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class Passo3Controller extends BaseController
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param number $apid
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $apid)
    {
        // $modelo = new Apropriacaonotaempenho();
        $modelo = new Apropriacaosituacao();
        $dados = $modelo->retornaListagemPasso3($apid);
        
        if ($request->ajax()) {
            $grid = DataTables::of($dados);

            $grid->editColumn('valor_agrupado', '{!! number_format(floatval($valor_agrupado), 2, ",", ".") !!}');
            $grid->addColumn('empenho', function ($linha) use ($apid) {
                $id = $linha->id;
                
                // Empenhos disponíveis
                return $this->mostraEmpenhos($id);
            })
            ->addColumn('fonte', function ($linha) {
                $id = $linha->id;
                
                // Fontes disponíveis
                return $this->mostraFontes($id);
            })
            ->addColumn('valor', function ($linha) use ($apid, $dados) {
                $id = $linha->id;
                $valorTotal = $linha->valor_agrupado;
                
                // Valores a ratear
                return $this->mostraValores($id, $valorTotal);
            });

            $grid->rawColumns(['empenho', 'fonte', 'valor']);

            return $grid->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.folha.apropriacao.passo3', compact('html'));
    }
    
    /**
     * Atualiza valor rateado por empenho
     *
     * @param number $id
     * @param number $valorRateado
     * @return \Illuminate\Http\Response
     */
    public function atualiza($id, $valorRateado)
    {
        $valor = '0';
        if ($valorRateado != '') {
            $valor = str_replace(',', '.', str_replace('.', '', $valorRateado));
        }
        
        $modelo = new Apropriacaonotaempenho();
        
        $registro = $modelo->find($id);
        $registro->valor_rateado = $valor;
        $registro->save();
    }
    
    /**
     * Verifica se pode ou não avançar ao próximo passo
     *
     * @param Request $request
     * @return boolean
     */
    public function verificaPodeAvancar(Request $request)
    {
        $apid = $request->apid;
        $modelo = new Apropriacaonotaempenho();
        
        // Verifica se já pode passar para o próximo passo
        $qtde = $modelo->retornaQtdeRegistrosInvalidos($apid);
        
        return ($qtde == 0);
    }
    
    /**
     * Retorna mensagem no caso de erro ao avançar
     *
     * @return string
     */
    protected function retornaMensagemErroAvanco()
    {
        return config('mensagens.apropriacao-empenho-pendencias');
    }
    
    /**
     * Monta $html com definições do Grid
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    private function retornaGrid()
    {
        $html = $this->htmlBuilder;
        
        $html->addColumn([
            'data' => 'situacao',
            'name' => 'situacao',
            'title' => 'Situação'
        ]);
        $html->addColumn([
            'data' => 'vpd',
            'name' => 'vpd',
            'title' => 'VPD'
        ]);
        $html->addColumn([
            'data' => 'natureza',
            'name' => 'natureza',
            'title' => 'Natureza <br />da despesa'
        ]);
        $html->addColumn([
            'data' => 'subitem',
            'name' => 'subitem',
            'title' => 'Sub Item'
        ]);
        $html->addColumn([
            'data' => 'valor_agrupado',
            'name' => 'valor_agrupado',
            'title' => 'Valor <br />a empenhar',
            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'empenho',
            'name' => 'empenho',
            'title' => 'Nota <br />de empenho',
            'orderable' => true,
            'searchable' => false
        ]);
        $html->addColumn([
            'data' => 'fonte',
            'name' => 'fonte',
            'title' => 'Fonte',
            'class' => 'text-right',
            'orderable' => false,
            'searchable' => false
        ]);
        $html->addColumn([
            'data' => 'valor',
            'name' => 'valor',
            'title' => 'Valor <br />a ratear',
            'class' => 'text-right',
            'orderable' => true,
            'searchable' => false
        ]);
        $html->addColumn([
            'data' => 'conta',
            'name' => 'conta',
            'visible' => false,
            'title' => 'ND Completa'
        ]);
        
        $html->parameters([
            'processing' => true,
            'serverSide' => true,
            'responsive' => true,
            'info' => true,
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
     * Retorna html para apresentação do campo Empenho no grid
     * 
     * @param number $id
     * @return string
     */
    private function mostraEmpenhos($id)
    {
        $modeloNe = new Apropriacaonotaempenho();
        $empenhosPorConta = $modeloNe->retornaEmpenhosPorId($id);
        
        $empenhos = '';
        foreach ($empenhosPorConta as $ne) {
            $empenhos .= $ne['empenho'] . '<br />';
        }
        
        if ($empenhos == '') {
            $qtdeEmpenhosNulos = session('identificacao.empenho.qtde.nulos', 0);
            
            $empenhos .= '<a href=';
            $empenhos .= route('crud.empenho.index');
            $empenhos .= '>';
            $empenhos .= 'Cadastrar NE';
            $empenhos .= '</a>';
            
            $qtdeEmpenhosNulos++;
            session(['identificacao.empenho.qtde.nulos' => $qtdeEmpenhosNulos]);
        }
        
        return $empenhos;
    }
    
    /**
     * Retorna html para apresentação do campo Fonte no grid
     * 
     * @param number $id
     * @return string
     */
    private function mostraFontes($id)
    {
        $modeloNe = new Apropriacaonotaempenho();
        $empenhosPorConta = $modeloNe->retornaEmpenhosPorId($id);
        
        $empenhos = '';
        foreach ($empenhosPorConta as $ne) {
            $empenhos .= $ne['fonte'] . '<br />';
        }
        
        return $empenhos;
    }
    
    /**
     * Retorna html para apresentação do campo Valor a ratear no grid
     * 
     * @param number $idSituacao
     * @param number  $valorTotal
     * @return string
     */
    private function mostraValores($idSituacao, $valorTotal)
    {
        $modeloNe = new Apropriacaonotaempenho();
        $empenhosPorConta = $modeloNe->retornaEmpenhosPorId($idSituacao);
        $qtdeEmpenhos = count($empenhosPorConta);
        
        $campos = '';
        $valores = '';
        
        foreach ($empenhosPorConta as $empenho) {
            $campoId = $empenho['id'];
            $campoValor = $empenho['valor_rateado'];
            $valorFormatado = $this->retornaValorFormatado($campoValor);
            
            $campos .= '';
            $campos .= "<input ";
            $campos .= "type='text' ";
            $campos .= "class='valor text-right' ";
            $campos .= "style='padding: 0px; height: 20px; width: 120px;' ";
            $campos .= "data-id='$campoId' ";
            $campos .= "value='$valorFormatado'";
            $campos .= '>';
            $campos .= '<br />';
            
            $valores .= $valorFormatado;
            $valores .= '<br />';
        }
        
        $retorno = '';
        $retorno .= '<div class="btn-group">';
        $retorno .= '<!-- ' . $qtdeEmpenhos . ' -->';
        $retorno .= '<!-- ' . $valorTotal . ' -->';
        
        if ($qtdeEmpenhos > 1) {
            $retorno .= "<form id='frm_$idSituacao'>";
            $retorno .= substr($campos, 0, - 6);
            $retorno .= "</form>";
        } else {
            $retorno .= substr($valores, 0, - 6);
        }
        
        $retorno .= '</div>';
        
        return $retorno;
    }
}
