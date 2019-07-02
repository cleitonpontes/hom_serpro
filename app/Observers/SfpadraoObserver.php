<?php

namespace App\Observers;

use App\Models\SfPadrao;

class SfpadraoObserver
{
    /**
     * Handle the sf padrao "created" event.
     *
     * @param  \App\Models\SfPadrao  $sfPadrao
     * @return void
     */
    public function created(SfPadrao $sfPadrao)
    {
        //
    }

    /**
     * Handle the sf padrao "updated" event.
     *
     * @param  \App\Models\SfPadrao  $sfPadrao
     * @return void
     */
    public function updated(SfPadrao $sfPadrao)
    {
        //
    }

    /**
     * Handle the sf padrao "deleted" event.
     *
     * @param  \App\Models\SfPadrao  $sfPadrao
     * @return void
     */
    public function deleted(SfPadrao $sfPadrao)
    {
        if($sfPadrao->categoriapadrao == 'EXECFOLHA'){
            $nsfpadrao = new SfPadrao();
            $nsfpadrao->where('fk','=',$sfPadrao->id)
                ->where('categoriapadrao','=','EXECFOLHAAPROPRIA')
                ->first();

            $fk = $nsfpadrao->id;
            $nsfpadrao->delete();

            $n2sfpadrao = new SfPadrao();
            $n2sfpadrao->where('fk','=',$fk)
                ->where('categoriapadrao','=','EXECFOLHAALTERA')
                ->get();

            foreach ($n2sfpadrao as $item) {
                $item->delete();
            }

        }
    }

    /**
     * Handle the sf padrao "restored" event.
     *
     * @param  \App\Models\SfPadrao  $sfPadrao
     * @return void
     */
    public function restored(SfPadrao $sfPadrao)
    {
        //
    }

    /**
     * Handle the sf padrao "force deleted" event.
     *
     * @param  \App\Models\SfPadrao  $sfPadrao
     * @return void
     */
    public function forceDeleted(SfPadrao $sfPadrao)
    {
        //
    }
}
