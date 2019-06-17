<?php

namespace App\Listeners;

use App\Events\ContratohistoricoEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContratoCronogramaListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ContratohistoricoEvent  $event
     * @return void
     */
    public function handle(ContratohistoricoEvent $event)
    {
        //
    }
}
