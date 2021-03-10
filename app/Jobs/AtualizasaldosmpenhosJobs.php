<?php

namespace App\Jobs;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
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
        array $contas_contabeis
    )
    {
        $this->ug = $ug;
        $this->amb = $amb;
        $this->contacorrente = $contacorrente;
        $this->mes = $mes;
        $this->ano = $ano;
        $this->empenhodetalhado = $empenhodetalhado;
        $this->contas_contabeis = $contas_contabeis;
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
            $retorno = $saldocontabilSta->saldocontabilAnoUgGestaoContacontabilContacorrente(
                $this->ano,
                $this->ug,
                $gestao,
                $contacontabil1,
                $this->contacorrente);

            if ($retorno != null) {
                $dado[$item] = $retorno['saldo'];
            } else {
                $dado[$item] = $saldoAtual;
            }

            $this->empenhodetalhado->fill($dado);
            $this->empenhodetalhado->push();
        }
    }
}
