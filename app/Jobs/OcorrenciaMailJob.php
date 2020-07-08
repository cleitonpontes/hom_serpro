<?php

namespace App\Jobs;

use App\Mail\EmailOcorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class OcorrenciaMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dadosocorrencia;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dadosocorrencia)
    {
        $this->dadosocorrencia = $dadosocorrencia;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->dadosocorrencia['emailpreposto'])
            ->cc($this->dadosocorrencia['responsaveis'])
            ->send(new EmailOcorrencia($this->dadosocorrencia));
    }
}
