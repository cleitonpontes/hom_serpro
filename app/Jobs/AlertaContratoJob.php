<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Notifications\RotinaAlertaContratoNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AlertaContratoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $usuario;

    public $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BackpackUser $usuario, RotinaAlertaContratoNotification $notification)
    {
        $this->usuario = $usuario;
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->usuario->notify($this->notification);
    }

}
