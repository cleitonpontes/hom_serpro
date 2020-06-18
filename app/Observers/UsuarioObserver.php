<?php

namespace App\Observers;

use App\Models\BackpackUser;
use App\Models\Contratoresponsavel;

class UsuarioObserver
{
    /**
     * Handle the backpack user "created" event.
     *
     * @param \App\Models\BackpackUser $backpackUser
     * @return void
     */
    public function created(BackpackUser $backpackUser)
    {
        //
    }

    /**
     * Handle the backpack user "updated" event.
     *
     * @param \App\Models\BackpackUser $backpackUser
     * @return void
     */
    public function updated(BackpackUser $backpackUser)
    {
        if ($backpackUser->situacao == false) {
            // Também inativa contratosresponsaveis
            $contratosResponsavel = Contratoresponsavel::where('user_id', $backpackUser->id);
            $contratosResponsavel->update(['situacao' => false]);
        }
    }

    /**
     * Handle the backpack user "deleted" event.
     *
     * @param \App\Models\BackpackUser $backpackUser
     * @return void
     */
    public function deleted(BackpackUser $backpackUser)
    {
        //
    }

    /**
     * Handle the backpack user "restored" event.
     *
     * @param \App\Models\BackpackUser $backpackUser
     * @return void
     */
    public function restored(BackpackUser $backpackUser)
    {
        //
    }

    /**
     * Handle the backpack user "force deleted" event.
     *
     * @param \App\Models\BackpackUser $backpackUser
     * @return void
     */
    public function forceDeleted(BackpackUser $backpackUser)
    {
        //
    }

}
