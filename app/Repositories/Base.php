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


    public function geraNonceSiafiEmpenho(int $minuta_id, int $remessa_id)
    {
        $amb = '';
        $config = config('app.app_amb');

        if($config == 'Ambiente Desenvolvimento AGU'){
            $amb = 'DEV-AGU';
        }

        if($config == 'Ambiente Homologação'){
            $amb = 'HOM';
        }

        if($config == 'Ambiente Treinamento'){
            $amb = 'TRE';
        }

        $nonce = $amb.date('Y').'_'.$minuta_id.'_'.$remessa_id;

        return $nonce;
    }

}
