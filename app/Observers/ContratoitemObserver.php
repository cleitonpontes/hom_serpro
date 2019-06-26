<?php

namespace App\Observers;

use App\Models\Contratoitem;

class ContratoitemObserver
{
    /**
     * Handle the contratoitem "created" event.
     *
     * @param  \App\Contratoitem $contratoitem
     * @return void
     */
    public function created(Contratoitem $contratoitem)
    {
        //
    }

    /**
     * Handle the contratoitem "updated" event.
     *
     * @param  \App\Contratoitem $contratoitem
     * @return void
     */
    public function updated(Contratoitem $contratoitem)
    {
        //
    }

    /**
     * Handle the contratoitem "deleted" event.
     *
     * @param  \App\Contratoitem $contratoitem
     * @return void
     */
    public function deleted(Contratoitem $contratoitem)
    {
        //
    }

    /**
     * Handle the contratoitem "restored" event.
     *
     * @param  \App\Contratoitem $contratoitem
     * @return void
     */
    public function restored(Contratoitem $contratoitem)
    {
        //
    }

    /**
     * Handle the contratoitem "force deleted" event.
     *
     * @param  \App\Contratoitem $contratoitem
     * @return void
     */
    public function forceDeleted(Contratoitem $contratoitem)
    {
        //
    }



}
