<?php
/**
 * Controller com métodos e funções do Passo 6 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Models\Apropriacao;
use App\Models\Apropriacaosituacao;
use App\Models\SfPadrao;
use App\Models\SfDadosBasicos;
use App\Models\SfDocOrigem;
use App\Models\SfPco;
use App\Models\SfPcoItem;
use App\Models\SfDespesaAnular;
use App\Models\SfDespesaAnularItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SfRelItemDespAnular;

/**
 * Disponibiliza as funcionalidades específicas para o Passo 6 - Persistir dados
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Passo6
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class Passo6Controller extends BaseController
{

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param number $apid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $apid)
    {
        return view('adminlte::mod.folha.apropriacao.passo6', compact('apid'));
    }

    /**
     * Método para chamadas, via ajax, de métodos para as tarefas de persistência de dados
     *
     * @param number $apid
     * @param string $nomeDados
     * @return \Illuminate\Http\Response
     */
    public function persistir($apid, $nomeDados)
    {
        $metodo = 'retornaRegistrosParaGravacao' . $nomeDados;

        if ($nomeDados == 'PreparacaoInicial') {
            $metodo = 'executa' . $nomeDados;
        }

        // NOTA: function_exists($metodo)) não foi utilizado propositalmente para gerar erro se não existir o método!
        $retorno = $this->$metodo($apid);

        return $retorno;
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

        $modelo = new SfPco();
        $podeAvancar = $modelo->validaPasso6($apid);

        return $podeAvancar;
    }

    /**
     * Retorna mensagem no caso de erro ao avançar
     *
     * @return string
     */
    protected function retornaMensagemErroAvanco()
    {
        return config('mensagens.apropriacao-persistir-pendencias');
    }

    /**
     * Prepara os dados de trabalho para persistência dos dados, colocando-os em memória
     *
     * @param number $apid
     */
    private function executaPreparacaoInicial($apid)
    {
        // Retorna dados preliminares
        $this->retornaDadosPreliminaresDaApropriacaoParaGravacao($apid);

        // Retorna dados PCO
        $this->retornaDadosPreliminaresDaApropriacaoParaGravacaoPco($apid);

        // Retorna dados Despesa a anular
        $this->retornaDadosPreliminaresDaApropriacaoParaGravacaoDespesaAnular($apid);

        // Elimina dados anteriores
        $this->eliminaDadosAnteriores($apid);
    }

    /**
     * Retorna dados da apropriação conforme $apid
     *
     * @param number $apid
     * @return array
     */
    private function retornaDadosPreliminaresDaApropriacaoParaGravacao($apid)
    {
        // Dados previamente dispostos em memória
        $dados = session('apropriacao.passo6.padrao.basico.docorigem');
        $dados = null;

        if ($dados == null) {
            // Se não houver dados na session, busca os dados no banco
            $modelo = new Apropriacao();
            $dados = $modelo->retornaDadosPasso6($apid);

            // Grava tais dados em memória
            session(['apropriacao.passo6.padrao.basico.docorigem' => $dados]);
        }

        return $dados;
    }

    /**
     * Retorna dados da apropriação conforme $apid
     *
     * @param number $apid
     * @return array
     */
    private function retornaDadosPreliminaresDaApropriacaoParaGravacaoPco($apid)
    {
        // Dados previamente dispostos em memória
        $dados = session('apropriacao.passo6.pco');
        $dados = null;

        if ($dados == null) {
            // Se não houver dados na session, busca os dados no banco
            $modelo = new Apropriacaosituacao();
            $dados = $modelo->retornaDadosPasso6Pco($apid);

            // Grava tais dados em memória
            session(['apropriacao.passo6.pco' => $dados]);
        }

        return $dados;
    }

    /**
     * Retorna dados da apropriação conforme $apid
     *
     * @param number $apid
     * @return array
     */
    private function retornaDadosPreliminaresDaApropriacaoParaGravacaoDespesaAnular($apid)
    {
        // Dados previamente dispostos em memória
        $dados = session('apropriacao.passo6.despesaanular');
        $dados = null;

        if ($dados == null) {
            // Se não houver dados na session, busca os dados no banco
            $modelo = new Apropriacaosituacao();
            $dados = $modelo->retornaDadosPasso6DespesaAnular($apid);

            // Grava tais dados em memória
            session(['apropriacao.passo6.despesaanular' => $dados]);
        }

        return $dados;
    }

    /**
     * Elimina possíveis registros anteriores de uma apropriação
     *
     * @param number $apid
     */
    private function eliminaDadosAnteriores($apid)
    {
        $modeloPadrao = new SfPadrao();
        $modeloPadrao->where('fk', $apid)->delete();
    }

    /**
     * Grava dados na SfPadrao, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoPadrao($apid)
    {
        // Buscar dados
        $dados = $this->retornaDadosPreliminaresDaApropriacaoParaGravacao($apid);

        $ug = $dados['ug'];
        $ano = $dados['ano'];

        // Gerar array
        $dadosPadrao = array();

        $dadosPadrao['fk'] = $apid;
        $dadosPadrao['categoriapadrao'] = 'EXECFOLHA';
        $dadosPadrao['decricaopadrao'] = Auth::user()->username;
        $dadosPadrao['codugemit'] = $ug;
        $dadosPadrao['anodh'] = $ano;
        $dadosPadrao['codtipodh'] = 'FL';
        $dadosPadrao['numdh'] = null;
        $dadosPadrao['dtemis'] = now();
        $dadosPadrao['txtmotivo'] = null;
        $dadosPadrao['msgretorno'] = null;
        $dadosPadrao['tipo'] = 'F';
        $dadosPadrao['situacao'] = 'P';

        // Gravar dados do array
        $novoRegistroPadrao = SfPadrao::create($dadosPadrao);
        $novoIdPadrao = $novoRegistroPadrao->id;

        // Salva array em memória para posterior gravação
        session(['apropriacao.passo6.padrao.novoid' => $novoIdPadrao]);
    }

    /**
     * Grava dados na SfDadosBasicos, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoDadosBasicos($apid)
    {
        // Buscar dados
        $dados = $this->retornaDadosPreliminaresDaApropriacaoParaGravacao($apid);

        $padraoId = session('apropriacao.passo6.padrao.novoid');
        $ug = $dados['ug'];
        $valorBruto = $dados['valor_bruto'];
        $observacoes = $dados['observacoes'];
        $nup = $dados['nup'];
        $ateste = $dados['ateste'];

        $ultimoDiaMes = $this->retornaUltimoDiaMes($ateste);

        // Gerar array
        $dadosBasico = array();

        $dadosBasico['sfpadrao_id'] = $padraoId;
        $dadosBasico['dtemis'] = now();
        $dadosBasico['dtvenc'] = $ultimoDiaMes;
        $dadosBasico['codugpgto'] = $ug;
        $dadosBasico['vlr'] = $valorBruto;
        $dadosBasico['txtobser'] = $observacoes;
        $dadosBasico['txtinfoadic'] = null;
        $dadosBasico['vlrtaxacambio'] = 0;
        $dadosBasico['txtprocesso'] = $nup;
        $dadosBasico['dtateste'] = $ateste;
        $dadosBasico['codcredordevedor'] = $ug;
        $dadosBasico['dtpgtoreceb'] = $ateste;

        // Gravar dados do array
        $novoRegistroBasico = SfDadosBasicos::create($dadosBasico);
        $novoIdBasico = $novoRegistroBasico->id;

        // Salva array em memória para posterior gravação
        session(['apropriacao.passo6.basico.novoid' => $novoIdBasico]);
    }

    /**
     * Grava dados na SfDocOrigem, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoDocumentoOrigem($apid)
    {
        // Buscar dados
        $dados = $this->retornaDadosPreliminaresDaApropriacaoParaGravacao($apid);

        $basicoId = session('apropriacao.passo6.basico.novoid');
        $ug = $dados['ug'];
        $docOrigem = $dados['doc_origem'];
        $valorBruto = $dados['valor_bruto'];

        // Gerar array
        $dadosDocOrigem = array();

        $dadosDocOrigem['sfdadosbasicos_id'] = $basicoId;
        $dadosDocOrigem['codidentemit'] = $ug;
        $dadosDocOrigem['dtemis'] = now();
        $dadosDocOrigem['numdocorigem'] = $docOrigem;
        $dadosDocOrigem['vlr'] = $valorBruto;

        SfDocOrigem::create($dadosDocOrigem);
    }

    /**
     * Grava dados na SfPco, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoPco($apid)
    {
        // Buscar dados
        $dados = $this->retornaDadosPreliminaresDaApropriacaoParaGravacaoPco($apid);

        $padraoId = session('apropriacao.passo6.padrao.novoid');
        $iArray = 0;
        $numSequenciaPco = 1;
        $numSequenciaPcoItem = 1;

        // Gerar array
        $dadosPco = array();
        $dadosPcoItem = array();
        $dadosRelacionamentosPco = array();
        $situacaoAnterior = '';

        foreach ($dados as $dado) {
            $ug = $dado['ug'];
            $situacao = $dado['situacao'];
            $empenho = $dado['empenho'];
            $subitem = $dado['subitem'];
            $vpd = $dado['vpd'];
            $valor = $dado['valor_rateado'];

            if ($situacao != $situacaoAnterior) {
                // Monta array para registro SfPco
                $dadosPco['sfpadrao_id'] = $padraoId;
                $dadosPco['numseqitem'] = $numSequenciaPco;
                $dadosPco['codsit'] = $situacao;
                $dadosPco['codugempe'] = $ug;

                // Gravar dados do array
                $novoRegistroPco = SfPco::create($dadosPco);
                $novoIdPco = $novoRegistroPco->id;

                $situacaoAnterior = $situacao;
                $numSequenciaPco ++;
                $numSequenciaPcoItem = 1;
            }

            // Monta array para registro SfPcoItem
            $dadosPcoItem[$iArray]['sfpco_id'] = $novoIdPco;
            $dadosPcoItem[$iArray]['numseqitem'] = $numSequenciaPcoItem;
            $dadosPcoItem[$iArray]['numempe'] = $empenho;
            $dadosPcoItem[$iArray]['codsubitemempe'] = $subitem;
            $dadosPcoItem[$iArray]['indrliquidado'] = 1;
            $dadosPcoItem[$iArray]['vlr'] = $valor;
            $dadosPcoItem[$iArray]['numclassa'] = $vpd;
            $dadosPcoItem[$iArray]['numclassb'] = 0;
            $dadosPcoItem[$iArray]['numclassc'] = 0;
            $dadosPcoItem[$iArray]['txtinscra'] = '';
            $dadosPcoItem[$iArray]['txtinscrb'] = '';
            $dadosPcoItem[$iArray]['txtinscrc'] = '';

            // Monta array alteranativo para futura busca de registros de relacionamentos PCO e DespesaAnular
            $dadosRelacionamentosPco[$iArray]['sfpco_id'] = $novoIdPco;
            $dadosRelacionamentosPco[$iArray]['sfpco_seq'] = $numSequenciaPco - 1;
            $dadosRelacionamentosPco[$iArray]['numseqitem'] = $numSequenciaPcoItem;
            $dadosRelacionamentosPco[$iArray]['numempe'] = $empenho;
            $dadosRelacionamentosPco[$iArray]['codsubitemempe'] = $subitem;
            $dadosRelacionamentosPco[$iArray]['numclassa'] = $vpd;
            $dadosRelacionamentosPco[$iArray]['vlr'] = $valor;

            $numSequenciaPcoItem ++;
            $iArray ++;
        }

        // Salva array em memória para posterior gravação
        session(['apropriacao.passo6.pcoitem.dados' => $dadosPcoItem]);
        session(['apropriacao.passo6.relacionamentos.pco.dados' => $dadosRelacionamentosPco]);
    }

    /**
     * Grava dados na SfPcoItem, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoPcoItem($apid)
    {
        // Buscar dados
        $dados = session('apropriacao.passo6.pcoitem.dados');

        // Gravar dados do array
        SfPcoItem::insert($dados);
    }

    /**
     * Grava dados na SfDespesaAnular, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoDespesa($apid)
    {
        // Buscar dados
        $dados = $this->retornaDadosPreliminaresDaApropriacaoParaGravacaoDespesaAnular($apid);

        $padraoId = session('apropriacao.passo6.padrao.novoid');
        $iArray = 0;
        $numSequenciaDespesa = 1;
        $numSequenciaDespesaItem = 1;

        // Gerar array
        $dadosDespesa = array();
        $dadosDespesaItem = array();
        $situacaoAnterior = '';

        foreach ($dados as $dado) {
            $ug = $dado['ug'];
            $situacao = $dado['situacao'];
            $empenho = $dado['empenho'];
            $subitem = $dado['subitem'];
            $vpd = $dado['vpd'];
            $valor = $dado['valor_rateado'];

            if ($situacao != $situacaoAnterior) {
                // Monta array para registro SfDespesaAnular
                $dadosDespesa['sfpadrao_id'] = $padraoId;
                $dadosDespesa['numseqitem'] = $numSequenciaDespesa;
                $dadosDespesa['codsit'] = $situacao;
                $dadosDespesa['codugempe'] = $ug;

                // TODO: Validar existência do correto PCO para cada Despesa a Anular

                // Gravar dados do array
                $novoRegistroDespesa = SfDespesaAnular::create($dadosDespesa);
                $novoIdDespesa = $novoRegistroDespesa->id;

                $situacaoAnterior = $situacao;
                $numSequenciaDespesa ++;
                $numSequenciaDespesaItem = 1;
            }

            // Monta array para registro SfDespesaAnularItem
            $dadosDespesaItem[$iArray]['sfdespesaanular_id'] = $novoIdDespesa;
            $dadosDespesaItem[$iArray]['numseqitem'] = $numSequenciaDespesaItem;
            $dadosDespesaItem[$iArray]['numempe'] = $empenho;
            $dadosDespesaItem[$iArray]['codsubitemempe'] = $subitem;
            $dadosDespesaItem[$iArray]['vlr'] = $valor;
            $dadosDespesaItem[$iArray]['numclassa'] = $vpd;

            $numSequenciaDespesaItem ++;
            $iArray ++;
        }

        // Salva array em memória para posterior gravação
        session(['apropriacao.passo6.despesaitem.dados' => $dadosDespesaItem]);
    }

    /**
     * Grava dados na SfDespesaAnularItem, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoDespesaItem($apid)
    {
        // Buscar dados dos itens de despesa a anular para gravar
        $dados = session('apropriacao.passo6.despesaitem.dados');

        $dadosRelacionamentos = array();
        $sequencia = 0;

        // Gravar dados do array
        foreach ($dados as $dado) {
            $novoRegistroDespesaItem = SfDespesaAnularItem::create($dado);
            $novoIdDespesaItem = $novoRegistroDespesaItem->id;

            // Busca registros Pco e Pco Item para relacionamentos
            $retorno = $this->retornaPcoAchado($dado);
            $valor = $dado['vlr'];

            // Monta array para SfRelItemDespAnular
            $dadosRelacionamentos[$sequencia]['sfdespanular_id'] = $novoIdDespesaItem;
            $dadosRelacionamentos[$sequencia]['numseqpai'] = $retorno['sfpco_seq'];
            $dadosRelacionamentos[$sequencia]['numseqitem'] = $retorno['numseqitem'];
            $dadosRelacionamentos[$sequencia]['vlr'] = $valor;

            $sequencia ++;
        }

        // Salva array em memória para posterior gravação
        session(['apropriacao.passo6.relacionamentos.dados' => $dadosRelacionamentos]);
    }

    /**
     * Grava dados na SfRelItemDespAnular, conforme apropriação $apid
     *
     * @param number $apid
     */
    private function retornaRegistrosParaGravacaoRelacionamentos($apid)
    {
        // Buscar dados dos relacionamentos a gravar
        $dadosRelacionamentos = session('apropriacao.passo6.relacionamentos.dados');

        // Gravar dados do array
        SfRelItemDespAnular::insert($dadosRelacionamentos);
    }

    /**
     * Verifica existência de Situação e VPD por registro da importação
     *
     * @param array $importacao
     * @return array
     */
    private function retornaPcoAchado($registro)
    {
        $semRetorno = array();

        // Dados de todas os PCOs Item - em memória
        $dadosRelacionamentosPco = session('apropriacao.passo6.relacionamentos.pco.dados');

        // Filtra o PCO Item existente para cada registro
        $achados = array_filter($dadosRelacionamentosPco, function ($pco) use ($registro) {
            return (
                $pco['numempe'] == $registro['numempe'] &&
                $pco['codsubitemempe'] == $registro['codsubitemempe']
            );
        });

        $semRetorno['sfpco_seq'] = null;
        $semRetorno['numseqitem'] = null;

        // Ajusta registro $achado, se for o caso
        $achado = isset($achados) ? array_shift($achados) : $semRetorno;

        return $achado;
    }

    /**
     * Retorna último dia do mês, considerando a data informada $dataConsiderar
     *
     * @param string $dataConsiderar
     * @return string
     */
    private function retornaUltimoDiaMes($dataConsiderar)
    {
        $primeiroDiaMes = $this->retornaPrimeiroDiaMes($dataConsiderar);

        $data = new \DateTime($primeiroDiaMes);

        $proximoMes = $data->add(new \DateInterval('P01M'));
        $ultimoDiaMes = $proximoMes->sub(new \DateInterval('P01D'));
        $dataFormatada = $ultimoDiaMes->format('Y-m-d');

        return $dataFormatada;
    }

    /**
     * Retorna primeiro dia do mês, considerando a data informada $dataConsiderar
     *
     * @param string $dataConsiderar
     * @return string
     */
    private function retornaPrimeiroDiaMes($dataConsiderar)
    {
        $data = new \DateTime($dataConsiderar);

        $dataFormatada = $data->format('Y-m-') . '01';

        return $dataFormatada;
    }
}
