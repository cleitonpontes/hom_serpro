<?php

namespace App\Notifications;

use App\Models\BackpackUser;
use App\Models\Contrato;
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

    public function __construct(BackpackUser $user, array $dado_email, $contratos)
    {
        $this->user = $user;
        $this->dado_email = $dado_email;
        $this->contratos = $contratos;
        $this->texto_html = $dado_email['texto'];
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
        $converter = new HtmlConverter();
        $texto_email = $converter->convert($this->texto_html);


        $contratos = [];
        foreach ($this->contratos as $contrato) {
            $contratos[] = [
                'numero' => $contrato->numero,
                'processo' => $contrato->processo,
                'cpf_cnpj_idgener' => $contrato->fornecedor->cpf_cnpj_idgener,
                'nome' => $contrato->fornecedor->nome,
                'objeto' => substr($contrato->objeto,0,100).'...',
                'valor_global' => $contrato->valor_global,
                'vigencia_inicio' => $contrato->vigencia_inicio,
                'vigencia_fim' => $contrato->vigencia_fim,
            ];
        }

        $mensagem = new MailMessage;
        $mensagem->subject('Rotina de Alerta ' . $this->dado_email['nomerotina']);
        $mensagem->markdown('emails.rotina.alertacontratos', [
            'texto' => $texto_email,
            'nomerotina' => $this->dado_email['nomerotina'],
            'telefones' => $this->dado_email['telefones'],
            'contratos' => $contratos
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
        $html = "<br>";
        $html .= "<table class=\"table table-striped table-hover table-bordered table-responsive\">";
        $html .= '<thead>
                        <tr>
                            <td align="center"><b>Número</b></td>
                            <td align="center"><b>Processo</b></td>
                            <td align="center"><b>Fornecedor</b></td>
                            <td align="center"><b>Objeto</b></td>
                            <td align="center"><b>Valor Global (R$)</b></td>
                            <td align="center"><b>Vig. início</b></td>
                            <td align="center"><b>Vig. fim</b></td>
                        </tr>
                        </thead>
                        <tbody>';
        foreach ($this->contratos as $contrato) {
            $html .= "<tr>";
            $html .= '<td align="center">'.$contrato->numero.'</td>';
            $html .= '<td align="center">'.$contrato->processo.'</td>';
            $html .= '<td>'.$contrato->fornecedor->cpf_cnpj_idgener.' - '.$contrato->fornecedor->nome.'</td>';
            $html .= '<td align="justify">'.substr($contrato->objeto,0,100).'...</td>';
            $html .= '<td align="right">'.number_format($contrato->valor_global,2,',','.').'</td>';
            $html .= '<td align="center">'.implode("/",array_reverse(explode("-",$contrato->vigencia_inicio))).'</td>';
            $html .= '<td align="center">'.implode("/",array_reverse(explode("-",$contrato->vigencia_fim))).'</td>';
            $html .= "</tr>";
        }
        $html .= '</tbody>';
        $html .= '</table>';


        return [
            'assunto' => 'Rotina de Alerta - ' . strtoupper($this->dado_email['nomerotina']),
            'mensagem' => $this->texto_html.$html,
            'anexos' => '',
        ];
    }
}
