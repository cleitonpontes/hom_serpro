<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Notifications\PasswordUserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserMailPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $dados;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BackpackUser $user, $dados)
    {
        $this->user = $user;
        $this->dados = $dados;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new PasswordUserNotification($this->dados));
    }
}
