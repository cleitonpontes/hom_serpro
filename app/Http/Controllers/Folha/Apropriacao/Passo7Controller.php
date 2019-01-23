<?php
namespace App\Http\Controllers\Folha\Apropriacao;

class Passo7Controller extends BaseController
{
    /**
     * Gerar XML - via chamada de rota
     * 
     * @TODO CRIAR RESPECTIVA ROTA
     * 
     * @param number $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function gerarXml($id)
    {
        return view('adminlte::mod.folha.apropriacao.passo7');
    }
    
}
