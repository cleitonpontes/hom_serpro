<?php

namespace App\Jobs;

use App\Models\DevolveMinutaSiasg;
use Exception;
use App\Http\Traits\CompraTrait;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItemUnidade;
use App\Models\Comprasitemunidadecontratoitens;
use App\Models\Contrato;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class EnviaEmpenhoSiasgJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use CompraTrait;

    /**
     * @var Contrato
     */
    private $contrato;

    private $dados;
    private $devolve_id;

    /**
     * Create a new job instance.
     *
     * @param $array
     * @param $devolve_id
     */
    public function __construct($array, $devolve_id)
    {
        $this->dados = $array;
        $this->devolve_id = $devolve_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $apiSiasg = new ApiSiasg();

        $retorno = $apiSiasg->executaConsulta('Empenho', $this->dados, 'POST');
        $devolve = DevolveMinutaSiasg::find($this->devolve_id);
        $devolve->mensagem_siasg = json_encode($retorno, JSON_UNESCAPED_UNICODE);
        $devolve->json_enviado = json_encode($this->dados, JSON_UNESCAPED_UNICODE);
        $devolve->situacao = $retorno['messagem'];
        $devolve->save();

    }
}
