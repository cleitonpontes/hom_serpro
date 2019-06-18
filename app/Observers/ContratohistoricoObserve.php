<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Models\Contratocronograma;
use App\Models\Contratohistorico;
use Illuminate\Support\Facades\DB;

class ContratohistoricoObserve
{

    public function __construct(Contratocronograma $contratocronograma)
    {
        $this->contratocronograma = $contratocronograma;
    }

    /**
     * Handle the contratohistorico "created" event.
     *
     * @param  \App\Contratohistorico $contratohistorico
     * @return void
     */
    public function created(Contratohistorico $contratohistorico)
    {
        $this->contratocronograma->inserirCronogramaFromHistorico($contratohistorico);
    }

    /**
     * Handle the contratohistorico "updated" event.
     *
     * @param  \App\Contratohistorico $contratohistorico
     * @return void
     */
    public function updated(Contratohistorico $contratohistorico)
    {
        $historico = Contratohistorico::where('contrato_id','=',$contratohistorico->contrato_id)
            ->orderBy('data_assinatura')
            ->get();

        foreach ($historico as $h){
            $this->contratocronograma->atualizaCronogramaFromHistorico($h);

            $arrayhistorico = $h->toArray();
            unset($arrayhistorico['contrato_id']);
            unset($arrayhistorico['id']);

            Contrato::where('id','=',$contratohistorico->contrato_id)
                ->update($arrayhistorico);

        }

    }

    /**
     * Handle the contratohistorico "deleted" event.
     *
     * @param  \App\Contratohistorico $contratohistorico
     * @return void
     */
    public function deleted(Contratohistorico $contratohistorico)
    {
        $contratohistorico->cronograma()->delete();
    }

    /**
     * Handle the contratohistorico "restored" event.
     *
     * @param  \App\Contratohistorico $contratohistorico
     * @return void
     */
    public function restored(Contratohistorico $contratohistorico)
    {
        //
    }

    /**
     * Handle the contratohistorico "force deleted" event.
     *
     * @param  \App\Contratohistorico $contratohistorico
     * @return void
     */
    public function forceDeleted(Contratohistorico $contratohistorico)
    {
        //
    }

}
