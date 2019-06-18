<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Models\Contratocronograma;

class ContratocronogramaObserve
{

    public function __construct(Contrato $contrato)
    {
        $this->contrato = $contrato;
    }

    /**
     * Handle the contratocronograma "created" event.
     *
     * @param  \App\Models\Contratocronograma  $contratocronograma
     * @return void
     */
    public function created(Contratocronograma $contratocronograma)
    {
        $this->contrato->atualizaValorAcumuladoFromCronograma($contratocronograma);
    }

    /**
     * Handle the contratocronograma "updated" event.
     *
     * @param  \App\Models\Contratocronograma  $contratocronograma
     * @return void
     */
    public function updated(Contratocronograma $contratocronograma)
    {
        $this->contrato->atualizaValorAcumuladoFromCronograma($contratocronograma);
    }

    /**
     * Handle the contratocronograma "deleted" event.
     *
     * @param  \App\Models\Contratocronograma  $contratocronograma
     * @return void
     */
    public function deleted(Contratocronograma $contratocronograma)
    {
        $this->contrato->atualizaValorAcumuladoFromCronograma($contratocronograma);
    }

    /**
     * Handle the contratocronograma "restored" event.
     *
     * @param  \App\Models\Contratocronograma  $contratocronograma
     * @return void
     */
    public function restored(Contratocronograma $contratocronograma)
    {
        //
    }

    /**
     * Handle the contratocronograma "force deleted" event.
     *
     * @param  \App\Models\Contratocronograma  $contratocronograma
     * @return void
     */
    public function forceDeleted(Contratocronograma $contratocronograma)
    {
        //
    }
}
