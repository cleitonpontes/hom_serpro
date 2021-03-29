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
//        $this->json = json_encode($array);
        $this->dados = $array;
        $this->devolve_id = $devolve_id;
//        $this->contrato = Contrato::find($dados['id']);
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
        $devolve->mensagem_siasg = json_encode($retorno);
        $devolve->save();

//        dd($retorno);
//
//        if ($retorno['messagem'] !== 'Sucesso') {
//            return 'Erro: ' . $retorno['messagem'];
//        } else {
//            return 'Teste Ok' . $retorno['messagem'];
//        }

        /*        $compraSiasg = $this->consultaCompraSiasg($this->dados);

                DB::beginTransaction();
                try {
                    if (isset($compraSiasg->data->compraSispp)) {
                        $params = $this->montaParametrosCompra($compraSiasg, $this->dados);

                        $compra = $this->updateOrCreateCompra($params);

                        if ($compraSiasg->data->compraSispp->tipoCompra == 1) {
                            $this->gravaParametroItensdaCompraSISPPCommand($compraSiasg, $compra);
                        }

                        if ($compraSiasg->data->compraSispp->tipoCompra == 2) {
                            $this->gravaParametroItensdaCompraSISRPCommand($compraSiasg, $compra);
                        }

                        $this->vincularItemCompraAoItemContrato($this->contrato, $this->dados, $compra);
                    }

                    DB::commit();
                } catch (Exception $exc) {
                    DB::rollback();
                    fail($exc);
                }*/
    }
}
