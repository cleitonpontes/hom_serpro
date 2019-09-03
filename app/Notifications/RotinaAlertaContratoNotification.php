<?php

namespace App\Notifications;

use App\Models\BackpackUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use League\HTMLToMarkdown\HtmlConverter;

class RotinaAlertaContratoNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $dado_email;
    protected $contratos;
    protected $texto_html;

    public function __construct(BackpackUser $user, array $dado_email, array $contratos)
    {
        $this->user = $user;
        $this->dado_email = $dado_email;
        $this->contratos = $contratos;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->texto_html = str_replace('!!nomeresponsavel!!', $this->dado_email['texto'], $this->user->name);
        $converter = new HtmlConverter();
        $texto_email = $converter->convert($this->texto_html);

        $mensagem = new MailMessage;
        $mensagem->subject('Rotina de Alerta ' . $this->dado_email['nomerotina']);
        $mensagem->markdown('emails.rotina.alertacontratos', [
            'texto' => $texto_email,
            'nomerotina' => $this->dado_email['nomerotina'],
            'telefones' => $this->dado_email['telefones'],
            'contratos' => $this->contratos
        ]);

        return $mensagem;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'assunto' => 'Rotina de Alerta ' . $this->dado_email['nomerotina'],
            'mensagem' => $this->texto_html,
            'anexos' => '',
        ];
    }
}
