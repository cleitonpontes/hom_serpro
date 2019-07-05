<?php

namespace App\Notifications;

use App\Models\BackpackUser;
use App\Models\Comunica;
use Html2Text\Html2Text;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ComunicaNotification extends Notification
{
    use Queueable;

    protected $comunica;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comunica $comunica, BackpackUser $user)
    {
        $this->comunica = $comunica;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'mail',
            'database',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $anexos = $this->comunica->anexos;

        $texto = new Html2Text($this->comunica->mensagem);

        $mensagem = new MailMessage;
        $mensagem->subject($this->comunica->assunto);
        $mensagem->greeting('Prezado(a) '.$this->user->name.',');
        $mensagem->line('Segue mensagem Comunica - Sistema Conta:');
        $mensagem->line('"'.$texto->getText().'"');
        $mensagem->action('Ler mensagens',url('/mensagens'));
        if($this->comunica->anexos){
            foreach ($anexos as $anexo){
                $mensagem->attach(env('APP_PATH')."storage/app/".$anexo);
            }
        }

        return $mensagem;

//        return (new MailMessage)
//            ->subject($this->comunica->assunto)
//            ->line($this->comunica->mensagem);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'assunto' => $this->comunica->assunto,
            'mensagem' => $this->comunica->mensagem,
            'anexos' => $this->comunica->anexos,
        ];
    }
}
