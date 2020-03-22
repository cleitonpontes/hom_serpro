<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\Subrogacao;

class SubrogacaoObserver
{
    public function created(Subrogacao $subrogacao)
    {
        $this->atualizaContrato($subrogacao->unidadedestino_id, $subrogacao->contrato_id);
        $this->atualizaHistorico($subrogacao->unidadedestino_id, $subrogacao->contrato_id);
    }


    public function deleted(Subrogacao $subrogacao)
    {
        $this->atualizaContrato($subrogacao->unidadeorigem_id, $subrogacao->contrato_id);
        $this->atualizaHistorico($subrogacao->unidadeorigem_id, $subrogacao->contrato_id);
    }

    private function atualizaContrato($unidadedestino,$contrato_id)
    {
        $contrato = Contrato::find($contrato_id);
        $contrato->unidade_id = $unidadedestino;
        $contrato->save();
    }

    private function atualizaHistorico($unidadedestino,$contrato_id)
    {

        $historicos = Contratohistorico::where('contrato_id',$contrato_id)->get();
        foreach ($historicos as $historico){
            $historico->unidade_id = $unidadedestino;
            $historico->save();
        }

    }

}
