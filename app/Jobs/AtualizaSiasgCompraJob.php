<?php

namespace App\Jobs;

use App\Models\Siasgcompra;
use App\XML\ApiSiasg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AtualizaSiasgCompraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siasgcompra;
    protected $tipoconsulta;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Siasgcompra $siasgcompra)
    {
        $this->siasgcompra = $siasgcompra;
        $this->tipoconsulta = 'Compra';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiSiasg = new ApiSiasg;
        $dado = [
            'ano' => $this->siasgcompra->ano,
            'modalidade' => $this->siasgcompra->modalidade->descres,
            'numero' => $this->siasgcompra->numero,
            'uasg' => $this->siasgcompra->unidade->codigosiasg
        ];

        $retorno = $apiSiasg->executaConsulta($this->tipoconsulta,$dado);

        $this->siasgcompra->json = $retorno;
        $this->siasgcompra->mensagem = $retorno->mensagem;

    }
}
