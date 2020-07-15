<?php

namespace App\Observers;

use App\Jobs\AtualizaSiasgContratoJob;
use App\Models\ContratoSiasgIntegracao;
use App\Models\Siasgcompra;
use App\Models\Siasgcontrato;
use App\XML\ApiSiasg;

class SiasgcontratoObserver
{

    public function created(\App\Models\Siasgcontrato $siasgcontrato)
    {
//        $this->importacao($siasgcontrato);
        AtualizaSiasgContratoJob::dispatch($siasgcontrato)->onQueue('siasgcontrato');
    }

    public function updated(Siasgcontrato $siasgcontrato)
    {
//        $this->importacao($siasgcontrato);
        AtualizaSiasgContratoJob::dispatch($siasgcontrato)->onQueue('siasgcontrato');
    }

    public function deleted(Siasgcontrato $siasgcontrato)
    {
        //
    }

    private function importacao(Siasgcontrato $siasgcontrato)
    {
        $tipoconsulta = 'ContratoSisg';
        $dado = [
            'contrato' => $siasgcontrato->unidade->codigosiasg . $siasgcontrato->tipo->descres . $siasgcontrato->numero . $siasgcontrato->ano
        ];

        if($siasgcontrato->sisg == false){
            $tipoconsulta = 'ContratoNaoSisg';
            $dado = [
                'contratoNSisg' => $siasgcontrato->unidade->codigosiasg . str_pad($siasgcontrato->codigo_interno, 10 , " ") . $siasgcontrato->tipo->descres . $siasgcontrato->numero . $siasgcontrato->ano
            ];
        }

        $apiSiasg = new ApiSiasg;
        $retorno = $apiSiasg->executaConsulta($tipoconsulta, $dado);
        $siasgcontrato_atualizado = $siasgcontrato->atualizaJsonMensagemSituacao($siasgcontrato->id, $retorno);

        $contratoSiagIntegracao = new ContratoSiasgIntegracao;
        $contrato = $contratoSiagIntegracao->executaAtualizacaoContratos($siasgcontrato_atualizado);

        if(isset($contrato->id)){
            $siasgcontrato_atualizado->contrato_id = $contrato->id;
            $siasgcontrato_atualizado->save();
        }

    }

}
