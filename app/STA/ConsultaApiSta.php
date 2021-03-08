<?php


namespace App\STA;


use App\Http\Controllers\AdminController;

class ConsultaApiSta
{
    protected $host_sta;

    public function __construct()
    {
        $this->host_sta = config('migracao.api_sta');
        backpack_auth()->check();
    }

    public function saldocontabilAnoUgGestaoContacontabilContacorrente(
        string $ano,
        string $ug,
        string $gestao,
        string $contacontabil,
        string $contacorrente
    )
   {
//        $base = new AdminController();
        $url = $this->host_sta . '/api/saldocontabil/ano/' . $ano . '/ug/' . $ug . '/gestao/' . $gestao . '/contacontabil/' . $contacontabil . '/contacorrente/' . $contacorrente;
//        $dados = $base->buscaDadosUrl($url);
        $dados = $this->buscaDadosUrl($url);

        $retorno = null;

        if ($dados != 'null') {
            $retorno = [
                'tiposaldo' => @$dados->tiposaldo,
                'saldo' => @number_format($dados->saldo, 2, '.', ''),
            ];
        }
        return $retorno;
    }

    public function saldocontabilAnoUgGestaoContacontabil(string $ano, string $ug, string $gestao, string $contacontabil)
    {
        $base = new AdminController();
        $url = $this->host_sta . '/api/saldocontabil/ano/' . $ano . '/ug/' . $ug . '/gestao/' . $gestao . '/contacontabil/' . $contacontabil;

        $dados = $base->buscaDadosUrl($url);

        $retorno = [];
        $pkCount = (is_array($dados) ? count($dados) : 0);
        if ($pkCount > 0) {
            foreach ($dados as $dado) {
                $retorno[] = [
                    'contacorrente' => $dado['conta_corrente'],
                    'tiposaldo' => $dado['tiposaldo'],
                    'saldo' => number_format($dado['saldo'], 2, '.', ''),
                    'updated_at' => $dado['updated_at']
                ];
            }
            return $retorno;
        }
        return $dados;
    }

    public function saldocontabilAnoUgGestao(string $ano, string $ug, string $gestao)
    {
        $base = new AdminController();
        $url = $this->host_sta . '/api/saldocontabil/ano/' . $ano . '/ug/' . $ug . '/gestao/' . $gestao;
        $dados = $base->buscaDadosUrl($url);

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

    public function buscaDadosUrl($url)
    {
        $opt = [
            "http" => [
                'timeout' => 99999,
                "method" => "GET",
                "header" => [
                    'Content-Type: application/json',
                ],
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ],
        ];

        return json_decode(file_get_contents($url, false, stream_context_create($opt)));
    }
}
