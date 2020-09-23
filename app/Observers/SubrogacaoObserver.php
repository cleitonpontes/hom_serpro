<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\Subrogacao;
use Illuminate\Support\Facades\DB;

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
            $sql_contrato = "UPDATE contratos SET unidade_id=".$unidadedestino." WHERE id=".$contrato_id;
            DB::update($sql_contrato);

            $sql = "UPDATE contratohistorico SET unidade_id=".$unidadedestino." WHERE contrato_id=".$contrato_id;
            DB::update($sql);

    }


}
