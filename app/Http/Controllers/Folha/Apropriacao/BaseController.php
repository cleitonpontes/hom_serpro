<?php
/**
 * Controller base para extensão de outras controllers da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Models\Apropriacao;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Html\Builder;

/**
 * Disponibiliza as funcionalidades básicas para controllers
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Base
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class BaseController extends Controller
{

    /**
     * Método construtor
     *
     * @param Builder $htmlBuilder
     */
    public function __construct(Builder $htmlBuilder)
    {
        $this->htmlBuilder = $htmlBuilder;
        backpack_auth()->check();
//        $this->middleware('web');
    }

    /**
     * Efetua validação para permitir ou não o avanço ao próximo passo
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function avancaPasso()
    {
        $request = Request();
        $apid = $request->apid;

        $passo = $this->retornaPasso();
        $passoProximo = $passo + 1;

        $podeAvancar = $this->verificaPodeAvancar($request);

        if (! $podeAvancar) {
            $mensagem = $this->retornaMensagemErroAvanco();
            $this->exibeMensagemAlerta($mensagem);

            $url = "/folha/apropriacao/passo/$passo/apid/$apid";
            return redirect($url)->withInput();
        }

        // Grava atualização ao próximo passo na apropriação
        $this->atualizaFase($apid, $passo);

        $url = "/folha/apropriacao/passo/$passoProximo/apid/$apid";
        return redirect($url)->withInput();
    }

    /**
     * Atualiza a fase da apropriação para o próximo passo
     *
     * @param number $apid
     * @param int $passo
     */
    protected function atualizaFase(int $apid, int $passo)
    {
        $apropriacao = Apropriacao::findOrFail($apid);

        $passoProximo = $passo + 1;
        $faseAtual = $apropriacao->fase_id;

        if ($passoProximo > $faseAtual) {
            $apropriacao->fase_id = $passoProximo;
            $apropriacao->save();
        }
    }

    /**
     * Apresenta mensagem conforme $status
     *
     * @param string $mensagem
     * @param string $status
     */
    protected function exibeMensagem($mensagem, $status = 'Sucesso')
    {
        switch ($status) {
            case 'Alerta':
                $this->exibeMensagemAlerta($mensagem);
                break;

            case 'Erro':
                $this->exibeMensagemErro($mensagem);
                break;

            default:
                $this->exibeMensagemSucesso($mensagem);
                break;
        }
    }

    /**
     * Apresenta mensagem de sucesso
     *
     * @param string $mensagem
     */
    protected function exibeMensagemSucesso($mensagem)
    {
        \Alert::success($mensagem)->flash();
//        \toast()->success($mensagem, 'Sucesso');
    }

    /**
     * Apresenta mensagem de alerta
     *
     * @param string $mensagem
     */
    protected function exibeMensagemAlerta($mensagem)
    {
        \Alert::warning($mensagem)->flash();
//        \toast()->warning($mensagem, 'Alerta');
    }

    /**
     * Apresenta mensagem de erro
     *
     * @param string $mensagem
     */
    protected function exibeMensagemErro($mensagem)
    {
        \Alert::error($mensagem)->flash();
//        \toast()->error($mensagem, 'Erro');
    }

    /**
     * Formata número
     *
     * @param number $valor
     * @return number
     */
    protected function retornaValorFormatado($valor)
    {
        if (! is_numeric($valor)) {
            return $valor;
        }

        return number_format(floatval($valor), 2, ',', '.');
    }

    /**
     * Retorna número do passo atual
     *
     * @return number
     */
    private function retornaPasso()
    {
        // Busca url da rota
        $request = Request();
        $url = $request->path();

        $partes = explode('/', $url);
        $proc = array_search('passo', $partes);

        // Define o passo atual
        $passo = (int) $partes[$proc + 1];

        return $passo;
    }
}
