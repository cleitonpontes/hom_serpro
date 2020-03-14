<?php

namespace App\Repositories;

use App\Models\Unidade;
use Html2Text\Html2Text;

class Base
{

    /**
     * Retorna id do Órgão pela Unidade informada
     *
     * @param $idUnidade
     * @return string
     * @todo Mover esse método para repo Orgão ou Unidade, e gerar ALIAS
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaOrgaoPorUnidade($idUnidade)
    {
        $orgao = '';

        $unidade = Unidade::find($idUnidade);
        if ($unidade) {
            $orgao = $unidade->orgao_id;
        }

        return $orgao;
    }

    /**
     * Retorna apenas texto puro do campo mensagem
     *
     * @param string $campoMsg
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getTextoPuroDeCampoMensagem($campoMsg = '')
    {
        $mensagem = '';

        if ($campoMsg != '') {
            $msgHtml = new Html2Text($campoMsg);
            $mensagem = $msgHtml->getText();
        }

        return $mensagem;
    }

}
