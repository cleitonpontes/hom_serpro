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
        $contrato = Contrato::find($contrato_id);
        $contrato->unidade_id = $unidadedestino;
        $contrato->save();

        $sql = "UPDATE contratohistorico SET unidade_id='$unidadedestino' WHERE contrato_id='$contrato->id'";
        $historico = DB::update($sql);

    }


}
