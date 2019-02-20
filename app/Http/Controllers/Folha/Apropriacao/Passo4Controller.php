<?php
/**
 * Controller com métodos e funções do Passo 4 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Models\Apropriacaonotaempenho;
use App\Models\Empenhodetalhado;
use App\XML\Execsiafi;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param number $apid
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $apid)
    {
        $modelo = new Apropriacaonotaempenho();
        $empenhos = $modelo->retornaListagemPasso4ComSaldos($apid);

        return view('backpack::mod.folha.apropriacao.passo4', compact('empenhos', 'apid'));
    }

    /**
     * Consulta saldo de empenho no SIAFI e o atualiza no banco
     *
     * @param string $ug
     * @param number $ano
     * @param number $mes
     * @param string $empenho
     * @param string $subitem
     * @return number
     */
    public function atualiza($ug, $ano, $mes, $empenho, $subitem)
    {
        $registro = array();
        
        $registro['ug'] = $ug;
        $registro['ano'] = $ano;
        $registro['mes'] = $mes;
        $registro['empenho'] = $empenho;
        $registro['subitem'] = $subitem;


        // Consulta saldo do empenho
        $saldoAtual = $this->consultaSaldoSiafi($registro);
        if($saldoAtual > 0) {
            // Atualiza o saldo retornado
            $this->atualizaSaldo($empenho, $subitem, $saldoAtual);
        }
        return $saldoAtual;
    }
    
    /**
     * Atualiza os saldos de todos os empenhos
     * 
     * @param number $apid
     */
    public function atualizaTodos($apid)
    {
        $modelo = new Apropriacaonotaempenho();
        $empenhos = $modelo->retornaListagemPasso4ComSaldos($apid);
        
        foreach($empenhos as $registro) {
            // Consulta saldo do empenho
            $saldoAtual = $this->consultaSaldoSiafi($registro);

            if($saldoAtual > 0){
                // Atualiza o saldo retornado
                $this->atualizaSaldo($registro['empenho'], $registro['subitem'], $saldoAtual);
            }

        }
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
        $valido = $modelo->validarPasso4($apid);
        
        return $valido;
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
     * Realiza consulta do saldo do empenho no SIAFI de um dado empenho + subitem
     *
     * @param array $registro
     * @return number|string
     */
    private function consultaSaldoSiafi($registro)
    {
        // Valores fixos
        $amb = 'PROD';
        $contacontabil1 = config('app.conta_contabil');
        $meses = array('', 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');

        $ug = $registro['ug'];
        $ano = $registro['ano'];
        $mes = $meses[(int) $registro['mes']];
        $empenho = $registro['empenho'];
        $subitem = $registro['subitem'];

        $contacorrente = 'N' . $empenho . str_pad($subitem, 2, '0', STR_PAD_LEFT);
        $saldoAtual = 0;

        try {
            $execsiafi = new Execsiafi();

            $retorno = null;
            $retorno = $execsiafi->conrazao($ug, $amb, $ano, $ug, $contacontabil1, $contacorrente, $mes);

            if (isset($retorno->resultado[4])) {
                $saldoAtual = (string) $retorno->resultado[4];
            }
        } catch (Exception $e) {
            // dd('Erro no validaSaldo()', $e);
        }

        return $saldoAtual;
    }

    /**
     * Grava registro com a atualização do saldo consultado
     * 
     * @param string $empenho
     * @param string $subitem
     * @param number $saldo
     */
    private function atualizaSaldo($empenho, $subitem, $saldo)
    {
        $ug = session('user_ug_id');

        $modelo = new Empenhodetalhado();

        $dados = $modelo->leftjoin('empenhos as E', 'E.id', '=', 'empenho_id');
        $dados->leftjoin('naturezasubitem AS S', function ($relacao) {
            $relacao->on('S.id', '=', 'naturezasubitem_id');
        });
        $dados->leftjoin('naturezadespesa AS N', function ($relacao) {
            $relacao->on('N.id', '=', 'S.naturezadespesa_id');
        });

        $dados->where('E.unidade_id', $ug);
        $dados->where('E.numero', $empenho);
        $dados->where('S.codigo', $subitem);


        // Atualiza saldo
        $dados->update(['empaliquidar' => $saldo]);

    }
}
