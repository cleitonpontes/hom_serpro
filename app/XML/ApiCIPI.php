<?php


namespace App\XML;


class ApiCIPI
{
    protected $url_servico;
    protected $codigoOrgao;
    protected $sistema;
    protected $context;

    public function __construct()
    {
        $this->url_servico = config('api-cipi.url');
        $this->codigoOrgao = config('api-cipi.codigo_orgao');
        $this->sistema = config('api-cipi.sistema');
    }

    public function executaConsulta(string $tipo, array $dado, string $method = null)
    {
        $nome_funcao = 'consulta' . $tipo;

        return $this->$nome_funcao($dado);
    }

    private function submit(string $servico, array $params, string $method = null)
    {
        $retorno = '';

        $servico_especifico = $this->retornaServicoEspecifico($servico);
        $url = $this->url_servico . $servico_especifico;

        $retorno = $this->executaEnvioPost($url, $params);
        return $retorno;

    }

    private function executaEnvioPost(string $url, array $params)
    {
        $data = '';

        $array_credenciais = [
            "credenciaisAcesso" => [
                "codigoOrgao" => $this->codigoOrgao,
                "cpf" => backpack_user()->cpf,
                "sistema" => $this->sistema
            ]
        ];

        $parametros = array_merge($array_credenciais,$params);

        $opts = [
            "http" => [
                "method" => "POST",
                "header" => [
                    'Content-Type: application/json',
                ],
                'content' => json_encode($parametros)
            ],
            'ssl' => [
                'verify_peer' => false,
            ]
        ];

        $context = stream_context_create($opts);

        try {
            $data = file_get_contents($url, false, $context);
        } catch (\Exception $e) {
            //var_dump($e);
        }

        return $data;
    }

    private function retornaServicoEspecifico(string $servico)
    {
        $complemento_url = '';

        switch ($servico) {
            case 'VALIDACIPI':
                $complemento_url = 'projetoinvestimento/validar';
                break;
        }
        return $complemento_url;
    }

    private function consultaValidaCipi(array $dado, string $method = null)
    {
        return $this->submit('VALIDACIPI', $dado, $method);
    }

}
