<?php

namespace App\Events;

use App\Models\Contratohistorico;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ContratocronogramaEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contratohistorico;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Contratohistorico $contratohistorico)
    {
        $this->contratohistorico = $contratohistorico;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
