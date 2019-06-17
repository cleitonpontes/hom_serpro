<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Models\Contratohistorico;

class ContratoObserve
{
    /**
     * Handle the contrato "created" event.
     *
     * @param  \App\Contrato  $contrato
     * @return void
     */
    public function created(Contrato $contrato)
{
    Contratohistorico::create($contrato->toArray()+['contrato_id' => $contrato->id]);
}

    /**
     * Handle the contrato "updated" event.
     *
     * @param  \App\Contrato  $contrato
     * @return void
     */
    public function updated(Contrato $contrato)
    {
        //
    }

    /**
     * Handle the contrato "deleted" event.
     *
     * @param  \App\Contrato  $contrato
     * @return void
     */
    public function deleted(Contrato $contrato)
    {
        $contrato->historico()->delete();
        $contrato->cronograma()->delete();
        $contrato->responsaveis()->delete();
        $contrato->garantias()->delete();
        $contrato->arquivos()->delete();
        $contrato->empenhos()->delete();
        $contrato->faturas()->delete();
        $contrato->ocorrencias()->delete();
        $contrato->terceirizados()->delete();
    }

    /**
     * Handle the contrato "restored" event.
     *
     * @param  \App\Contrato  $contrato
     * @return void
     */
    public function restored(Contrato $contrato)
    {
        //
    }

    /**
     * Handle the contrato "force deleted" event.
     *
     * @param  \App\Contrato  $contrato
     * @return void
     */
    public function forceDeleted(Contrato $contrato)
    {
        //
    }
}
