<?php


namespace App\XML;


class ApiSiasg
{
    protected $url_servico;
    protected $token;
    protected $context;

    public function __construct()
    {
        $this->url_servico = config('api-siasg.url');
        $this->token = config('api-siasg.token');

    }

    public function executaConsulta(string $tipo, array $dado, string $method = null)
    {
        $nome_funcao = 'consulta' . $tipo;

        if ($method == 'POST') {
            $nome_funcao = 'envia' . $tipo;
            return $this->$nome_funcao($dado, $method);
        }

        $this->context = $this->montaHeader();

        return $this->$nome_funcao($dado);
    }

    private function montaHeader()
    {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "X-Authentication: " . $this->token
            ],
            'ssl' => [
                'verify_peer' => false,
            ]
        ];

        $context = stream_context_create($opts);

        return $context;
    }

    private function submit(string $servico, array $params, string $method = null)
    {
        $retorno = '';

        $servico_especifico = $this->retornaServicoEspecifico($servico);
        $url = $this->url_servico . $servico_especifico;

        if ($method == 'POST') {
            $retorno = $this->executaEnvioPost($url,$params);
            return $retorno;
        }

        $parametros = $this->trataParametros($params);

        try {
            $retorno = file_get_contents($url . $parametros, false, $this->context);
        } catch (\Exception $e) {
            //var_dump($e);
        }

        return $retorno;
    }

    private function executaEnvioPost(string $url, array $params)
    {
        $opts = [
            "http" => [
                "method" => "POST",
                "header" => [
                    "X-Authentication: " . $this->token,
                    'Content-Type: application/json',
                ],
                'content' => json_encode($params)
            ]
        ];

        $context = stream_context_create($opts);

        $data = file_get_contents($url, false, $context);

        return json_decode($data, true);
    }

    private function retornaServicoEspecifico(string $servico)
    {
        $complemento_url = '';

        switch ($servico) {
            case 'CONTRATOCOMPRA':
                $complemento_url = 'contrato/v1/contratos?';
                break;
            case 'CONTRATOSISG':
                $complemento_url = 'contrato/v1/contrato?';
                break;
            case 'CONTRATONAOSISG':
                $complemento_url = 'contrato/v1/contratonsisg?';
                break;
            case 'DADOSCONTRATO':
                $complemento_url = 'contrato/v1/dadoscontrato?';
                break;
            case 'COMPRASISPP':
                $complemento_url = 'compra/v1/sispp?';
                break;
            case 'ATUALIZASIASGEMPENHO':
                $complemento_url = 'compra/v1/atualizaSiasgEmpenho';
                break;
        }
        return $complemento_url;
    }

    private function consultaCompra(array $dado_consulta)
    {
//        $params = [
//            'ano' => $dado_consulta['ano'],
//            'modalidade' => $dado_consulta['modalidade'],
//            'numero' => $dado_consulta['numero'],
//            'uasg' => $dado_consulta['uasg']
//        ];

        return $this->submit('CONTRATOCOMPRA', $dado_consulta);
    }

    private function enviaEmpenho(array $dado, string $method)
    {
        return $this->submit('ATUALIZASIASGEMPENHO', $dado, $method);
    }


    private function consultaContratoCompra(array $dado_consulta)
    {
//        $params = [
//            'ano' => $dado_consulta['ano'],
//            'modalidade' => $dado_consulta['modalidade'],
//            'numero' => $dado_consulta['numero'],
//            'uasg' => $dado_consulta['uasg']
//        ];

        return $this->submit('CONTRATOCOMPRA', $dado_consulta);
    }

    private function consultaContratoSisg(array $dado_consulta)
    {
//        $params = [
//            'contrato' => $dado_consulta['id_contrato'],
//        ];

        return $this->submit('CONTRATOSISG', $dado_consulta);
    }

    private function consultaContratoNaoSisg(array $dado_consulta)
    {
//        $params = [
//            'contratoNSisg' => $dado_consulta['id_contrato'],
//        ];

        return $this->submit('CONTRATONAOSISG', $dado_consulta);
    }

    private function consultaDadosContrato(array $dado_consulta)
    {
        return $this->submit('DADOSCONTRATO', $dado_consulta);
    }

    private function consultaCompraSispp(array $dado_consulta)
    {
        return $this->submit('COMPRASISPP', $dado_consulta);
    }

    private function trataParametros(array $params)
    {
        return http_build_query($params, '', '&');
    }

    public function consultaCompraByUrl($url)
    {
        if(!isset($this->context)){
            $this->context = $this->montaHeader();
        }

        $retorno = file_get_contents($url, false, $this->context);
        return json_decode($retorno, true);
    }

}
