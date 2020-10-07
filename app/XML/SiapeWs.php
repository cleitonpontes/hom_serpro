<?php


namespace App\XML;

class SiapeWs
{

    public $resultado = [];

    protected function conexao_xml($amb)
    {

        if ($amb == 'PROD') {
            //ambiente produção
            $wsdl = 'https://www1.siapenet.gov.br/WSSiapenet/services/ConsultaSIAPE?wsdl';
        }

        if ($amb == 'HOM') {
            $wsdl = 'http://hom1.siapenet.gov.br/WSSiapenet/services/ConsultaSIAPE?wsdl';
        }

        $arrContextOptions = array("ssl" => array("verify_peer" => false, "verify_peer_name" => false));

        $options = array(
            'exceptions' => true,
            'trace' => 1,
            'stream_context' => stream_context_create($arrContextOptions)
        );

        $client = new \SoapClient($wsdl, $options);

        return $client;

    }

    protected function getCredenciais()
    {
        return config('siapews.credenciais');
    }

    public function consultaDadosPessoais($cpf, $orgao, $amb = 'PROD')
    {
        $client = $this->conexao_xml($amb);
        $credenciais = $this->getCredenciais();

        try {

            $client->consultaDadosPessoais(
                new \SoapParam($credenciais['siglaSistema'], 'siglaSistema'),
                new \SoapParam($credenciais['nomeSistema'], 'nomeSistema'),
                new \SoapParam($credenciais['senha'], 'senha'),
                new \SoapParam($cpf, 'cpf'),
                new \SoapParam($orgao, 'codOrgao'),
                new \SoapParam('b', 'parmExistPag'),
                new \SoapParam('c', 'parmTipoVinculo')
            );

        } catch (\Exception $e) {
            //var_dump($e);
        }

        return $client->__getLastResponse();
    }

    public function listaUorgs($orgao, $uorg, $amb = 'PROD')
    {
        $client = $this->conexao_xml($amb);
        $credenciais = $this->getCredenciais();

        try {

            $client->listaUorgs(
                new \SoapParam($credenciais['siglaSistema'], 'siglaSistema'),
                new \SoapParam($credenciais['nomeSistema'], 'nomeSistema'),
                new \SoapParam($credenciais['senha'], 'senha'),
                new \SoapParam($credenciais['cpf'], 'cpf'),
                new \SoapParam($orgao, 'codOrgao'),
                new \SoapParam($uorg, 'codUorg')
            );

        } catch (\Exception $e) {
            //var_dump($e);
        }

        return $client->__getLastResponse();
    }


}
