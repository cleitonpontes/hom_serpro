<?php
/**
 * Controller com métodos e funções do Passo 7 da Apropriação da Folha
 *
 * @author Basis Tecnologia da Informação
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 */
namespace App\Http\Controllers\Folha\Apropriacao;

/**
 * Disponibiliza as funcionalidades específicas para o Passo 7 - Gerar XML
 *
 * @category Conta
 * @package Conta_Folha_Apropriacao_Passo7
 * @author Anderson Sathler M. Ribeiro <asathler@gmail.com>
 * @copyright AGU - Advocacia Geral da União ©2018 <http://www.agu.gov.br>
 * @copyright Basis Tecnologia da Informação ©2018 <http://www.basis.com.br>
 * @license MIT License. <https://opensource.org/licenses/MIT>
 */
class Passo7Controller extends BaseController
{

    /**
     * Gerar XML - via chamada de rota
     *
     * @todo CRIAR RESPECTIVA ROTA
     *      
     * @param number $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function gerarXml($id)
    {
        return view('adminlte::mod.folha.apropriacao.passo7');
    }
}
