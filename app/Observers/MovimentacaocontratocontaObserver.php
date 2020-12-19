<?php

namespace App\Observers;

// use App\Movimentacaocontratoconta;

use App\Models\Movimentacaocontratoconta;


class MovimentacaocontratocontaObserver
{

    public function deleting()
    {

        \Log::info('Passou por aqui!!! - MovimentacaocontratocontaObserver.php - deleting');
        // $post->comments()->delete();
    }


    /**
     * Handle the movimentacaocontratoconta "created" event.
     *
     * @param  \App\Movimentacaocontratoconta  $movimentacaocontratoconta
     * @return void
     */
    public function created()
    {
        //
        \Log::info('Passou por aqui!!! - MovimentacaocontratocontaObserver.php - created');

    }

    /**
     * Handle the movimentacaocontratoconta "updated" event.
     *
     * @param  \App\Movimentacaocontratoconta  $movimentacaocontratoconta
     * @return void
     */
    public function updated()
    {
        //
        \Log::info('Passou por aqui!!! - MovimentacaocontratocontaObserver.php - updated');


    }

    /**
     * Handle the movimentacaocontratoconta "deleted" event.
     *
     * @param  \App\Movimentacaocontratoconta  $movimentacaocontratoconta
     * @return void
     */
    public function deleted()
    {
        \Log::info('Passou por aqui!!! - MovimentacaocontratocontaObserver.php - deleted');

        //
    }

    /**
     * Handle the movimentacaocontratoconta "restored" event.
     *
     * @param  \App\Movimentacaocontratoconta  $movimentacaocontratoconta
     * @return void
     */
    public function restored()
    {
        //
        \Log::info('Passou por aqui!!! - MovimentacaocontratocontaObserver.php - restored');

    }

    /**
     * Handle the movimentacaocontratoconta "force deleted" event.
     *
     * @param  \App\Movimentacaocontratoconta  $movimentacaocontratoconta
     * @return void
     */
    public function forceDeleted()
    {
        //
        \Log::info('Passou por aqui!!! - MovimentacaocontratocontaObserver.php - forceDeleted');

    }
}
