<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\Comunica;
use App\Notifications\ComunicaNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotificaUsuarioComunicaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    protected $comunica;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comunica $comunica, BackpackUser $user)
    {
        $this->comunica = $comunica;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new ComunicaNotification($this->comunica, $this->user));
    }
}
