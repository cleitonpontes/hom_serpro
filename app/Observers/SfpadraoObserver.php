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
