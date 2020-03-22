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
    }


    public function deleted(Subrogacao $subrogacao)
    {
        $this->atualizaContrato($subrogacao->unidadeorigem_id, $subrogacao->contrato_id);
    }

    private function atualizaContrato($unidadedestino,$contrato_id)
    {
        $contrato = Contrato::find($contrato_id);
        $contrato->unidade_id = $unidadedestino;
        $contrato->save();

        $historicos = $contrato->historico()->get();
        foreach ($historicos as $historico){
            $historico->unidade_id = $unidadedestino;
            $historico->save();
        }
    }


}
