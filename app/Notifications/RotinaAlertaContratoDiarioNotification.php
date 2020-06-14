<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use League\HTMLToMarkdown\HtmlConverter;

class RotinaAlertaContratoDiarioNotification extends Notification
{
    use Queueable;

    protected $dadosEmail;
    protected $dadosContrato;

    public function __construct(array $dadosEmail, $dadosContrato)
    {
        $this->dadosEmail = $dadosEmail;
        $this->dadosContrato = $dadosContrato;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $dadosEmail = $this->dadosEmail;
        $dadosContrato = $this->dadosContrato;

        $converter = new HtmlConverter();

        $textoEmail = mb_convert_encoding($converter->convert($dadosEmail['texto']), 'UTF-8', 'UTF-8');
        $objeto = $dadosContrato->objeto;
        $usuarios = $dadosEmail['usuarios'];

        $contratos[] = [
            'numero' => $dadosContrato->numero,
            'processo' => $dadosContrato->processo,
            'cpf_cnpj_idgener' => $dadosContrato->fornecedor->cpf_cnpj_idgener,
            'nome' => $dadosContrato->nome,
            'objeto' => (strlen($objeto) > 100 ? substr($objeto, 0, 100) . '...' : $objeto),
            'valor_global' => $dadosContrato->valor_global,
            'vigencia_inicio' => $dadosContrato->vigencia_inicio,
            'vigencia_fim' => $dadosContrato->vigencia_fim,
        ];

        $mensagem = new MailMessage;

        $mensagem->subject('Rotina de Alerta ' . $dadosEmail['nomerotina']);
        $mensagem->markdown('emails.rotina.alertacontratos', [
            'texto' => $textoEmail,
            'nomerotina' => $dadosEmail['nomerotina'],
            'telefones' => $dadosEmail['telefones'],
            'contratos' => $contratos
        ]);

        foreach($usuarios as $usuario) {
            $mensagem->cc($usuario->email);
        }

        return $mensagem;
    }

}
