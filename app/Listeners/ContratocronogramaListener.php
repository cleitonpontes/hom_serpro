<?php

namespace App\Listeners;

use App\Events\ContratocronogramaEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContratocronogramaListener
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
     * @param  ContratocronogramaEvent  $event
     * @return void
     */
    public function handle(ContratocronogramaEvent $event)
    {
        //
    }
}
