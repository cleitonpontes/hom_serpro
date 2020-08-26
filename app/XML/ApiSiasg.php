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
        $this->context = $this->montaHeader();
    }

    public function executaConsulta(string $tipo, array $dado)
    {
        $nome_funcao = 'consulta' . $tipo;

        return $this->$nome_funcao($dado);
    }

    private function montaHeader()
    {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "X-Authentication: " . $this->token
            ]
        ];

        $context = stream_context_create($opts);

        return $context;
    }

    private function submit(string $servico, array $params)
    {
        $retorno = '';

        $servico_especifico = $this->retornaServicoEspecifico($servico);
        $url = $this->url_servico . $servico_especifico;
        $parametros = $this->trataParametros($params);
        try {
            $retorno = file_get_contents($url . $parametros, false, $this->context);
        } catch (\Exception $e) {
            //var_dump($e);
        }

        return $retorno;
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

    private function trataParametros(array $params)
    {
        return http_build_query($params,'','&');
    }

}
