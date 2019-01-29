<?php
/**
 * Controller com métodos e funções do Passo 4 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Models\Apropriacaonotaempenho;
use App\XML\Execsiafi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

/**
 * Disponibiliza as funcionalidades específicas para o Passo 4 - Validar Saldos
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Passo4
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class Passo4Controller extends BaseController
{
    
    /**
     * Constante para texto de saldo suficiente
     * 
     * @var string
     */
    const SALDO_SUFICIENTE = 'Saldo suficiente';
    
    /**
     * Constante para texto de saldo insuficiente
     *
     * @var string
     */
    const SALDO_INSUFICIENTE = 'Saldo insuficiente';
    
    /**
     * Show the form for creating a new resource.
     * 
     * @param Request $request
     * @param number $apid
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $apid)
    {
        $dados = $this->getDataEmpenho($apid);
        
        if ($request->ajax()) {
            $grid = DataTables::of($dados);
            
            $grid->editColumn('saldo_necessario', '{!! number_format(floatval($saldo_necessario), 2, ",", ".") !!}');
            $grid->editColumn('saldo_atual', '{!! number_format(floatval($saldo_atual), 2, ",", ".") !!}');
            
            $grid->editColumn('utilizacao', function ($params) {
                $campo = $params['utilizacao'];
                $txtSaldo = self::SALDO_SUFICIENTE;
                
                $resultado = ($campo <> $txtSaldo) ? '<span style="color: red">' . $campo . '</span>' : $campo;
                
                return $resultado;
            });
            
            $grid->rawColumns(['utilizacao']);

            return $grid->make(true);
        }
        
        $html = $this->retornaGrid();
        
        return view('adminlte::mod.folha.apropriacao.passo4', compact('html'));
    }

    /**
     * Retorna dados de empenhos e já efetua suas respectivas validações de saldos via WebService do SIAFI
     * 
     * @param number $id
     * @return mixed
     */
    public function getDataEmpenho($id)
    {
        $modelo = new Apropriacaonotaempenho();
        
        $importacoes = $modelo->retornaListagemPasso4($id);
        $dados = $this->validaSaldo($importacoes);

        return $dados;
    }

    /**
     * Verifica se pode ou não avançar ao próximo passo
     *
     * @param Request $request
     * @return boolean
     */
    public function verificaPodeAvancar(Request $request)
    {
        $valid = session('apropriacao.valida.saldo.avanca');
        
        return ($valid == 'true');
    }

    /**
     * Retorna mensagem no caso de erro ao avançar
     *
     * @return string
     */
    protected function retornaMensagemErroAvanco()
    {
        return config('mensagens.apropriacao-saldo-pendencias');
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
            'data' => 'empenho',
            'name' => 'empenho',
            'title' => 'Nº do Empenho'
        ]);
        $html->addColumn([
            'data' => 'subitem',
            'name' => 'subitem',
            'title' => 'Sub Item'
        ]);
        $html->addColumn([
            'data' => 'fonte',
            'name' => 'fonte',
            'title' => 'Fonte'
        ]);
        $html->addColumn([
            'data' => 'saldo_necessario',
            'name' => 'saldo_necessario',
            'title' => 'Saldo Necessário',
            // 'type' => 'html',
            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'saldo_atual',
            'name' => 'saldo_atual',
            'title' => 'Saldo Atual',
            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'utilizacao',
            'name' => 'utilizacao',
            'title' => 'Utilização'
        ]);
        
        $html->parameters([
            'processing' => true,
            'serverSide' => true,
            'responsive' => true,
            'info' => true,
            'autoWidth' => true,
            'paging' => true,
            'lengthChange' => true,
            'language' => [
                'url' => "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
            ]
        ]);
        
        return $html;
    }
    
    /**
     * Valida saldo por registro dos $dados
     * 
     * @param mixed $params
     * @return mixed
     */
    private function validaSaldo($dados)
    {
        session(['apropriacao.valida.saldo.avanca' => 'true']);
        
        // Valores fixos
        $amb = 'PROD';
        $contacontabil1 = config('app.conta_contabil');
        $count = 0;
        $meses = array('', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
        
        foreach ($dados as $registro) {
            $ug = $registro['ug'];
            $ano = $registro['ano'];
            $mes = $meses[(int) $registro['mes']];
            $contacorrente = 'N' . $registro['empenho'] . str_pad($registro['subitem'], 2, '0', STR_PAD_LEFT);
            $saldoAtual = 0;
            
            try {
                $execsiafi = new Execsiafi();
                $retorno = null;
                $retorno = $execsiafi->conrazao($ug, $amb, $ano, $ug, $contacontabil1, $contacorrente, $mes);
                
                if (is_array($retorno)) {
                    if (isset($retorno->resultado[4])) {
                        $saldoAtual = $retorno->resultado[4];
                    }
                }
            } catch(Exception $e) {
                // dd($e);
            }
            
            $dados[$count]['saldo_atual'] = $saldoAtual;
            $dados[$count]['utilizacao'] = self::SALDO_SUFICIENTE;
            
            if ($registro['saldo_necessario'] > $saldoAtual) {
                $dados[$count]['utilizacao'] = self::SALDO_INSUFICIENTE;
                session(['apropriacao.valida.saldo.avanca' => 'false']);
            }
            $count ++;
        }
        
        return $dados;
    }
}
