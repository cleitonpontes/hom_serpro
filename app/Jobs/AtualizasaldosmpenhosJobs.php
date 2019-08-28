<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\Empenhodetalhado;
use App\Models\Unidade;
use App\STA\ConsultaApiSta;
use App\XML\Execsiafi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AtualizasaldosmpenhosJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ug;
    protected $amb;
    protected $contacorrente;
    protected $mes;
    protected $ano;
    protected $empenhodetalhado;
    protected $contas_contabeis;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        string $ug,
        string $amb,
        string $ano,
        string $contacorrente,
        string $mes,
        Empenhodetalhado $empenhodetalhado,
        array $contas_contabeis,
        BackpackUser $user
    ) {
        $this->ug = $ug;
        $this->amb = $amb;
        $this->contacorrente = $contacorrente;
        $this->mes = $mes;
        $this->ano = $ano;
        $this->empenhodetalhado = $empenhodetalhado;
        $this->contas_contabeis = $contas_contabeis;
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dado = [];
        foreach ($this->contas_contabeis as $item => $valor) {

            $contacontabil1 = $valor;
            $saldoAtual = 0;

            $unidade = Unidade::where('codigo', $this->ug)
                ->first();
            $gestao = $unidade->gestao;

            $saldocontabilSta = new ConsultaApiSta();
            $retorno = null;
            $retorno = $saldocontabilSta->saldocontabilUgGestaoContacontabilContacorrente(
                $this->ug,
                $gestao,
                $contacontabil1,
                $this->contacorrente);

            if ($retorno!=null) {
                $dado[$item] = $retorno['saldo'];
            } else {
                $dado[$item] = $saldoAtual;
            }


            //consulta saldo via webservice
//            $execsiafi = new Execsiafi();
//
//            $retorno = null;
//            $retorno = $execsiafi->conrazaoUser(
//                $this->ug,
//                $this->amb,
//                $this->ano,
//                $this->ug,
//                $contacontabil1,
//                $this->contacorrente,
//                $this->mes,
//                $this->user);
//
//            if (isset($retorno->resultado[0])) {
//                if ($retorno->resultado[0] == 'SUCESSO') {
//                    if (isset($retorno->resultado[4])) {
//                        $saldoAtual = (float)$retorno->resultado[4];
//                    }
//                    $dado[$item] = $saldoAtual;
//                }
//            }

        }

        $this->empenhodetalhado->fill($dado);
        $this->empenhodetalhado->push();

    }

}
