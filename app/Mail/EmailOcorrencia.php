<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOcorrencia extends Mailable
{
    use Queueable, SerializesModels;

    public $dadosocorrencia;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dadosocorrencia)
    {
        $this->dadosocorrencia = $dadosocorrencia;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $anexos = $this->dadosocorrencia['arquivos'];

        $mensagem = $this->markdown('emails.notificaOcorrencia')
            ->subject(config('app.name') . ' - Notificação de Ocorrência')
            ->with([
                'dadosocorrencia' => $this->dadosocorrencia,
            ]);

        $mensagem->attach(env('APP_PATH')."public/robots.txt");

        if($anexos){
            foreach ($anexos as $anexo){
                $mensagem->attach(env('APP_PATH')."storage/app/".$anexo);
            }
        }

        return $mensagem;

    }
}
