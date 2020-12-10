<?php

namespace App\Observers;

use App\Lancamento;

class LancamentoObserver
{


    public function deleting()
    {

        \Log::info('Passou por aqui!!! - LancamentoObserver.php - deleting');
        // $post->comments()->delete();
    }



    /**
     * Handle the lancamento "created" event.
     *
     * @param  \App\Lancamento  $lancamento
     * @return void
     */
    public function created()
    {
        //
        \Log::info('Passou por aqui!!! - LancamentoObserver.php - created');

    }

    /**
     * Handle the lancamento "updated" event.
     *
     * @param  \App\Lancamento  $lancamento
     * @return void
     */
    public function updated()
    {
        //
        \Log::info('Passou por aqui!!! - LancamentoObserver.php - updated');

    }

    /**
     * Handle the lancamento "deleted" event.
     *
     * @param  \App\Lancamento  $lancamento
     * @return void
     */
    public function deleted()
    {
        //
        \Log::info('Passou por aqui!!! - LancamentoObserver.php - deleted');

    }

    /**
     * Handle the lancamento "restored" event.
     *
     * @param  \App\Lancamento  $lancamento
     * @return void
     */
    public function restored()
    {
        //
        \Log::info('Passou por aqui!!! - LancamentoObserver.php - restored');

    }

    /**
     * Handle the lancamento "force deleted" event.
     *
     * @param  \App\Lancamento  $lancamento
     * @return void
     */
    public function forceDeleted()
    {
        //
        \Log::info('Passou por aqui!!! - LancamentoObserver.php - forceDeleted');

    }
}
