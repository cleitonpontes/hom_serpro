<?php

namespace App\Listeners;

use App\Events\ContratocronogramaEvent;
use App\Events\ContratoInsertEvent;
use App\Events\ContratohistoricoEvent;
use App\Models\Contratohistorico;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContratohistoricoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Contratohistorico $contratohistorico)
    {
        $this->contratohistorico = $contratohistorico;
    }

    /**
     * Handle the event.
     *
     * @param  ContratohistoricoEvent  $event
     * @return void
     */
    public function handle(ContratohistoricoEvent $event)
    {

        $this->contratohistorico->createNewHistorico($event);

        event(new ContratocronogramaEvent($this->contratohistorico));

    }
}
