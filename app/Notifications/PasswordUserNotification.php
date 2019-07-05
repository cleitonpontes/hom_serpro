<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordUserNotification extends Notification
{
    use Queueable;

    protected $dados;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = config('app.url');

        return (new MailMessage)
            ->subject(config('app.name') . ' - Senha de Usuário')
            ->greeting("Olá {$this->dados['nome']}!")
            ->line('Você foi cadastrado no Sistema Conta - Gestão de Contratos. Seus dados de acesso são:')
            ->line('Usuário: '.$this->dados['cpf'])
            ->line('Senha: '.$this->dados['senha'])
            ->line('Para acessar o sistema, clique no botão abaixo.')
            ->action('Acessar Sistema Conta', $url)
            ->line('Quaisquer dúvidas, procure o setor de contratos que atende sua Unidade!');
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
            //
        ];
    }
}
