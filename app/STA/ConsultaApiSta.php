<?php


namespace App\STA;


class ConsultaApiSta
{
    protected $host_sta;

    public function __construct()
    {
        $this->host_sta = config('migracao.api_sta');
        backpack_auth()->check();
    }

    public function saldocontabilUgGestaoContacontabilContacorrente(
        string $ug,
        string $gestao,
        string $contacontabil,
        string $contacorrente
    ) {
        $dados = json_decode(file_get_contents($this->host_sta . '/api/saldocontabil/ug/' . $ug . '/gestao/' . $gestao . '/contacontabil/' . $contacontabil . '/contacorrente/' . $contacorrente),
            true);

        $retorno = null;

        if($dados != null){
            $retorno = [
                'tiposaldo' => $dados['tiposaldo'],
                'saldo' => number_format($dados['saldo'], 2, '.', ''),
            ];
        }

        return $retorno;
    }

    public function saldocontabilUgGestaoContacontabil(string $ug, string $gestao, string $contacontabil)
    {
        $dados = json_decode(file_get_contents($this->host_sta . '/api/saldocontabil/ug/' . $ug . '/gestao/' . $gestao . '/contacontabil/' . $contacontabil),
            true);

        $retorno = [];
        foreach ($dados as $dado) {
            $retorno[] = [
                'contacorrente' => $dado['conta_corrente'],
                'tiposaldo' => $dado['tiposaldo'],
                'saldo' => number_format($dado['saldo'], 2, '.', '')
            ];
        }

        return $retorno;
    }

    public function saldocontabilUgGestao(string $ug, string $gestao)
    {
        $dados = json_decode(file_get_contents($this->host_sta . '/api/saldocontabil/ug/' . $ug . '/gestao/' . $gestao),
            true);

        $retorno = [];
        foreach ($dados as $dado) {
            $retorno[] = [
                'contacontabil' => $dado['conta_contabil'],
                'contacorrente' => $dado['conta_corrente'],
                'tiposaldo' => $dado['tiposaldo'],
                'saldo' => number_format($dado['saldo'], 2, '.', '')
            ];
        }

        return $retorno;
    }

}
