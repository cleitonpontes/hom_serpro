<?php


namespace App\Http\Controllers\Acessogov;


use App\Http\Controllers\AdminController;

class LoginAcessoGov
{
    protected $host_acessogov;

    public function __construct()
    {
        $this->host_acessogov = 'sso.staging.acesso.gov.br';

    }

    public function autorizacao() {

        $response_type = 'code';
        $client_id	= '123456';
        $scope = 'openid+email+phone+profile+govbr_confiabilidades';
        $redirect_uri = 'http://comprasnet.gov.br';
        $nonce =  '1597536582';//valor aleatório - Item obrigatório.
        $state = '98765431'; //Item não obrigatório.

        //https://sso.staging.acesso.gov.br/authorize?response_type=code&client_id=ec4318d6-f797-4d65-b4f7-39a33bf4d544&scope=openid+email+phone+profile&redirect_uri=http%3A%2F%2Fappcliente.com.br%2Fphpcliente%2Floginecidadao.Php&nonce=3ed8657fd74c&state=358578ce6728b

        $base = new AdminController();
        $url = $this->host_acessogov . '/authorize?response_type=' . $response_type . '&client_id=' . $client_id . '&scope=' . $scope . '&redirect_uri=' . $redirect_uri . '&nonce=' . $nonce.'&state='.$state;

        $dados = $base->buscaDadosUrl($url);

        dd($dados);

        $retorno = null;

        if($dados != null){
            $retorno = [
                'code' => $dados['code'],
                'state' => $dados['state']
            ];
        }

        return $retorno;
    }

    public function tokenAcesso() {

        $dados = $this->autorizacao();
        $redirect_uri = 'http://comprasnet.gov.br/acessogov/tokenacesso';

        $base = new AdminController();
        $url = $this->host_acessogov . '/token?response_type=authorization_code&code='.$dados['code'].'&redirect_uri='.$redirect_uri;

        $dados = $base->buscaDadosUrl($url);

        dd($dados);

        $retorno = null;

        if($dados != null){
            $retorno = [
                'code' => $dados['code'],
                'state' => $dados['state']
            ];
        }

        return $retorno;
    }

}
