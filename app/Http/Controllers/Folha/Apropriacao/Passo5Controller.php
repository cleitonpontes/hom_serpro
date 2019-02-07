<?php
/**
 * Controller com métodos e funções do Passo 5 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

use App\Forms\ApropriacaoPasso5Form;
use App\Models\Apropriacao;
use Illuminate\Http\Request;

/**
 * Disponibiliza as funcionalidades específicas para o Passo 5 - Informar Dados Complementares
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Passo5
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class Passo5Controller extends BaseController
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param number $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $apropriacao = Apropriacao::findOrFail($id)->toArray();

        // Valores iniciais
        $apropriacao = $this->retornaValoresIniciaisApropriacao($apropriacao);

        $form = \FormBuilder::create(ApropriacaoPasso5Form::class, [
            'url' => route('folha.apropriacao.passo.5.salva'),
            'method' => 'PUT',
            'model' => $apropriacao
        ]);

        return view('backpack::mod.folha.apropriacao.passo5', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $dados = $request->all();

        $pathRetorno = "/folha/apropriacao/passo/5/apid/$id";

        $apropriacao = Apropriacao::findOrFail($id);
        $apropriacao->update($dados);

        $mensagem = config('mensagens.apropriacao-dados-complementares-salvos');
        $this->exibeMensagemSucesso($mensagem);

        return redirect($pathRetorno)->withInput();
    }

    /**
     * Verifica se pode ou não avançar ao próximo passo
     *
     * @param Request $request
     * @return boolean
     */
    public function verificaPodeAvancar(Request $request)
    {
        $apropriacao = new Apropriacao();
        $podeAvancar = $apropriacao->validarPasso5($request->apid);

        return $podeAvancar;
    }

    /**
     * Retorna mensagem no caso de erro ao avançar
     *
     * @return string
     */
    protected function retornaMensagemErroAvanco()
    {
        return config('mensagens.apropriacao-dados-complementares-pendencias');
    }

    /**
     * Retorna array da $apropriacao com valores iniciais alterados em caso de prévios nulos
     *
     * @param array $apropriacao
     * @return array
     */
    private function retornaValoresIniciaisApropriacao($apropriacao)
    {
        $inicioAteste = date('Y-m-d');
        $inicioDocOrigem = 'DDP-' . $apropriacao['nivel'];

        $inicioObs = '';
        $inicioObs .= 'FOLHA DE PAGAMENTO';
        $inicioObs .= chr(13);
        $inicioObs .= $apropriacao['competencia'];
        $inicioObs .= ', Níveis: ';
        $inicioObs .= $apropriacao['nivel'];

        $ateste = is_null($apropriacao['ateste']) ? $inicioAteste : $apropriacao['ateste'];
        $docOrigem = is_null($apropriacao['doc_origem']) ? $inicioDocOrigem : $apropriacao['doc_origem'];
        $obs = is_null($apropriacao['observacoes']) ? $inicioObs : $apropriacao['observacoes'];

        $apropriacao['ateste'] = $ateste;
        $apropriacao['doc_origem'] = $docOrigem;
        $apropriacao['observacoes'] = $obs;

        return $apropriacao;
    }
}
