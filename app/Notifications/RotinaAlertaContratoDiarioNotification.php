<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use League\HTMLToMarkdown\HtmlConverter;

class RotinaAlertaContratoDiarioNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    protected $dadosEmail;

    /**
     * @var array
     */
    protected $usuarios;

    /**
     * @var array Todos os contratos
     */
    protected $contratos;

    /**
     * @var array Único contrato
     */
    protected $contrato;

    /**
     * Método construtor da classe
     *
     * @param array $dadosEmail
     * @param mixed $contratos
     * @param array $usuarios
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function __construct(array $dadosEmail, $contratos, $usuarios = [])
    {
        $this->dadosEmail = $dadosEmail;
        $this->contratos = $contratos;
        $this->usuarios = $usuarios;
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
        $contratosTodos = $this->contratos;

        foreach($contratosTodos as $contrato) {
            $this->contrato = $contrato;

            $contratosComDadosFormatados[] = [
                'numero' => $this->retornaContratoNumero(),
                'processo' => $this->retornaContratoProcesso(),
                'cpf_cnpj_idgener' => $this->retornaContratoFornecedorCnpj(),
                'nome' => $this->retornaContratoFornecedorNome(),
                'objeto' => $this->retornaContratoObjeto(),
                'valor_global' => $this->retornaContratoValorGlobal(),
                'vigencia_inicio' => $this->retornaContratoVigenciaInicio(),
                'vigencia_fim' => $this->retornaContratoVigenciaFim()
            ];
        }

        $mensagem = new MailMessage();

        $mensagem->subject($this->retornaEmailAssunto());
        $mensagem->markdown('emails.rotina.alertacontratos', [
            'texto' => $this->retornaEmailTexto(),
            'nomerotina' => $this->retornaEmailRotina(),
            'telefones' => $this->retornaEmailTelefones(),
            'contratos' => $contratosComDadosFormatados
        ]);

        foreach ($this->usuarios as $usuario) {
            $mensagem->cc($usuario->email);
        }

        return $mensagem;
    }

    /**
     * Get the default representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function toArray($notifiable)
    {
        return [
            'assunto' => $this->retornaEmailAssunto(),
            'mensagem' => $this->retornaMensagemHtml(),
            'anexos' => $this->retornaEmailAnexo()
        ];
    }

    /**
     * Retorna conteúdo html para composição da mensagem a ser enviada
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaMensagemHtml()
    {
        $contratos = $this->contratos;

        $dataVigenciaInicioExibicao = $this->retornaContratoVigenciaInicioExibicao();
        $dataVigenciaFimExibicao = $this->retornaContratoVigenciaFimExibicao();

        $html = '';
        $html .= $this->retornaEmailTexto();
        $html .= '<br />';
        $html .= "<table class='table table-striped table-hover table-bordered table-responsive'>";

        $html .= '<thead>';
        $html .= '<tr>';
        $html .= "<td align='center'> Números </td>";
        $html .= "<td align='center'> Processo </td>";
        $html .= "<td align='left'> Fornecedor </td>";
        $html .= "<td align='center'> Objeto </td>";
        $html .= "<td align='center'> Valor global (R$) </td>";
        $html .= "<td align='center'> Vig. início </td>";
        $html .= "<td align='center'> Vig. fim </td>";
        $html .= '<tr>';
        $html .= '</thead>';

        $html .= '<tbody>';

        foreach($contratos as $contrato) {
            $this->contrato = $contrato;

            $html .= '<tr>';
            $html .= "<td align='center'> " . $this->retornaContratoNumero() . " </td>";
            $html .= "<td align='center'> " . $this->retornaContratoProcesso() . " </td>";
            $html .= "<td align='left'> " . $this->retornaContratoFornecedor() . " </td>";
            $html .= "<td align='justify'> " . $this->retornaContratoObjeto() . " </td>";
            $html .= "<td align='right'> " . $this->retornaContratoValorGlobalFomatado() . " </td>";
            $html .= "<td align='center'> " . $dataVigenciaInicioExibicao . " </td>";
            $html .= "<td align='center'> " . $dataVigenciaFimExibicao . " </td>";
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        $htmlEncode = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

        return $htmlEncode;
    }

    /**
     * Retorna assunto do email
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaEmailAssunto()
    {
        $rotina = $this->retornaEmailRotina();

        return 'Rotina de Alerta - ' . $rotina;
    }

    /**
     * Retorna a rotina executada que gerou o email
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaEmailRotina()
    {
        return $this->retornaEmailCampo('nomerotina');
    }

    /**
     * Retorna o texto do corpo do email
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaEmailTexto()
    {
        $texto = $this->retornaEmailCampo('texto');
        $emailTexto = $this->retornaTextoConvertidoUtf8($texto);

        return $emailTexto;
    }

    /**
     * Retorna o(s) telefone(s) a serem exibidos no email
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaEmailTelefones()
    {
        return $this->retornaEmailCampo('telefones');
    }

    /**
     * Retorna um ou mais anexos a ser incluído no email
     *
     * @todo Verificar necessidade, ou não, deste método
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaEmailAnexo()
    {
        return '';
    }

    /**
     * Retorna o número do contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoNumero()
    {
        return $this->retornaContratoCampo('numero');
    }

    /**
     * Retorna o número do processo do contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoProcesso()
    {
        return $this->retornaContratoCampo('processo');
    }

    /**
     * Retorna o número do CNPJ do fornecedor do contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoFornecedorCnpj()
    {
        return $this->retornaContratoCampo('fornecedor', 'cpf_cnpj_idgener');
    }

    /**
     * Retorna o nome do fornecedor do contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoFornecedorNome()
    {
        return $this->retornaContratoCampo('fornecedor', 'nome');
    }

    /**
     * Retorna descrição do fornecedor do contrato, contendo CPNJ e nome
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoFornecedor()
    {
        $cnpj = $this->retornaContratoFornecedorCnpj();
        $nome = $this->retornaContratoFornecedorNome();

        return $cnpj . ' - ' . $nome;
    }

    /**
     * Retorna o objeto do contrato, limitado aos primeiros 100 caracteres
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoObjeto()
    {
        $objeto = $this->retornaContratoCampo('objeto');
        $retorno = strlen($objeto) > 100 ? substr($objeto, 0, 100) . '...' : $objeto;

        return $retorno;
    }

    /**
     * Retorna o valor global do contrato
     *
     * @return number
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoValorGlobal()
    {
        return $this->retornaContratoCampo('valor_global');
    }

    /**
     * Retorna o valor global do contrato, formatado com valor pt-br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoValorGlobalFomatado()
    {
        $valor = $this->retornaContratoCampo('valor_global');
        $valorFormatado = number_format($valor, 2, ',', '.');

        return $valorFormatado;
    }

    /**
     * Retorna a data de início da vigência do contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoVigenciaInicio()
    {
        return $this->retornaContratoCampo('vigencia_inicio');
    }

    /**
     * Retorna a data de início de vigência do contrato, em formato de data pt-br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoVigenciaInicioExibicao()
    {
        $data = $this->retornaContratoCampo('vigencia_inicio');

        return $this->retornaDataReversaExibicao($data);
    }

    /**
     * Retorna a data de fim da vigência do contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoVigenciaFim()
    {
        return $this->retornaContratoCampo('vigencia_fim');
    }

    /**
     * Retorna a data de fim de vigência do contrato, em formato de data pt-br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoVigenciaFimExibicao()
    {
        $data = $this->retornaContratoCampo('vigencia_fim');

        return $this->retornaDataReversaExibicao($data);
    }

    /**
     * Retorna data em formato de banco de dados (yyyy-mm-dd) para formato de data pt-br
     *
     * @param string $data
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaDataReversaExibicao($data)
    {
        $dataReversa = array_reverse(explode('-', $data));
        $dataExibicao = implode('/', $dataReversa);

        return $dataExibicao;
    }

    /**
     * Retorna @campo específico do array (dados do email), se o mesmo estiver presente
     *
     * @param string $campo
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaEmailCampo($campo = '')
    {
        $emailDados = $this->dadosEmail;
        $emailCampo = isset($emailDados[$campo]) ? $emailDados[$campo] : '';

        return $emailCampo;
    }

    /**
     * Retorna o @campo1 do objeto ou o @campo2 específico do objeto (dados do contrato), se o mesmo estiver presente
     *
     * @param string $campo1
     * @param string $campo2
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratoCampo($campo1 = '', $campo2 = '')
    {
        if ($campo1 == '' && $campo2 == '') {
            return '';
        }

        $contrato = $this->contrato;
        $campo = isset($contrato->$campo1) ? $contrato->$campo1 : '';

        if ($campo2 != '') {
            $campo = isset($contrato->$campo1->$campo2) ? $contrato->$campo1->$campo2 : '';
        }

        return $campo;
    }

    /**
     * Retorna texto convertido em html com formato utf-8
     *
     * @param string $texto
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaTextoConvertidoUtf8($texto = '')
    {
        $converter = new HtmlConverter();

        $txtConvertido = $converter->convert($texto);

        return mb_convert_encoding($txtConvertido, 'UTF-8', 'UTF-8');
    }

}
