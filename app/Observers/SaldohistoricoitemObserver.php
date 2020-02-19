<?php

namespace App\Observers;

use App\Models\Saldohistoricoitem;

class SaldohistoricoitemObserver
{
    /**
     * Handle the saldohistoricoitem "created" event.
     *
     * @param  \App\Models\Saldohistoricoitem  $saldohistoricoitem
     * @return void
     */
    public function created(Saldohistoricoitem $saldohistoricoitem)
    {
        //
    }

    /**
     * Handle the saldohistoricoitem "updated" event.
     *
     * @param  \App\Models\Saldohistoricoitem  $saldohistoricoitem
     * @return void
     */
    public function updated(Saldohistoricoitem $saldohistoricoitem)
    {
        //
    }

    /**
     * Handle the saldohistoricoitem "deleted" event.
     *
     * @param  \App\Models\Saldohistoricoitem  $saldohistoricoitem
     * @return void
     */
    public function deleted(Saldohistoricoitem $saldohistoricoitem)
    {
        //
    }

    /**
     * Handle the saldohistoricoitem "restored" event.
     *
     * @param  \App\Models\Saldohistoricoitem  $saldohistoricoitem
     * @return void
     */
    public function restored(Saldohistoricoitem $saldohistoricoitem)
    {
        //
    }

    /**
     * Handle the saldohistoricoitem "force deleted" event.
     *
     * @param  \App\Saldohistoricoitem  $saldohistoricoitem
     * @return void
     */
    public function forceDeleted(Saldohistoricoitem $saldohistoricoitem)
    {
        //
    }
}
