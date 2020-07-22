<?php

namespace App\Jobs;

use App\Models\ContratoSiasgIntegracao;
use App\Models\Siasgcontrato;
use App\XML\ApiSiasg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AtualizaSiasgContratoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siasgcontrato;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Siasgcontrato $siasgcontrato)
    {
        $this->siasgcontrato = $siasgcontrato;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tipoconsulta = 'ContratoSisg';

        $dado = [
            'contrato' => $this->siasgcontrato->unidade->codigosiasg . $this->siasgcontrato->tipo->descres . $this->siasgcontrato->numero . $this->siasgcontrato->ano
        ];

        if($this->siasgcontrato->sisg == false){

            $tipoconsulta = 'ContratoNaoSisg';

            $dado = [
                'contratoNSisg' => $this->siasgcontrato->unidade->codigosiasg . str_pad($this->siasgcontrato->codigo_interno, 10 , " ") . $this->siasgcontrato->tipo->descres . $this->siasgcontrato->numero . $this->siasgcontrato->ano
            ];

        }

        $apiSiasg = new ApiSiasg;
        $retorno = $apiSiasg->executaConsulta($tipoconsulta, $dado);
        $siasgcontrato_atualizado = $this->siasgcontrato->atualizaJsonMensagemSituacao($this->siasgcontrato->id, $retorno);

        if($siasgcontrato_atualizado->mensagem == 'Sucesso' and $siasgcontrato_atualizado->situacao == 'Importado'){
            $contratoSiagIntegracao = new ContratoSiasgIntegracao;
            $contrato = $contratoSiagIntegracao->executaAtualizacaoContratos($siasgcontrato_atualizado);

            if(isset($contrato->id)){
                $siasgcontrato_atualizado->contrato_id = $contrato->id;
                $siasgcontrato_atualizado->save();
            }
        }


    }


}
