<?php

namespace App\Http\Controllers\Publicacao;

use Alert;
use App\Jobs\AtualizaSituacaoPublicacaoJob;
use App\Jobs\PublicaPreviewOficioJob;
use App\Models\Codigoitem;
use App\Models\Contratohistorico;
use App\Models\ContratoPublicacoes;
use App\Models\Empenho;
use Exception;
use Illuminate\Support\Carbon;
use SoapHeader;
use SoapVar;
use PHPRtfLite;
use PHPRtfLite_Font;

class DiarioOficialClass extends BaseSoapController
{

    private $soapClient;
    private $securityNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private $Urlwsdl;
    private $username;
    private $password;


    public function __construct()
    {
        $this->Urlwsdl = config("publicacao.sistema.diario_oficial_uniao");
        $this->username = env('PUBLICACAO_DOU_USER');
        $this->password = env('PUBLICACAO_DOU_PWD');

        self::setWsdl($this->Urlwsdl);
        $node1 = new SoapVar($this->username, XSD_STRING, null, null, 'Username', $this->securityNS);
        $node2 = new SoapVar($this->password, XSD_STRING, null, null, 'Password', $this->securityNS);
        $token = new SoapVar(array($node1, $node2), SOAP_ENC_OBJECT, null, null, 'UsernameToken', $this->securityNS);
        $security = new SoapVar(array($token), SOAP_ENC_OBJECT, null, null, 'Security', $this->securityNS);
        $headers[] = new SOAPHeader($this->securityNS, 'Security', $security, false);

        $this->soapClient = InstanceSoapClient::init($headers);
    }

    public function consultaTodosFeriado()
    {
        try {
            $response = $this->soapClient->ConsultaTodosOrgaosPermitidos();

            dd($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function consultaSituacaoOficio($oficio_id)
    {
        try {
            $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
            $dados ['dados']['IDOficio'] = $oficio_id;

            return $this->soapClient->ConsultaAcompanhamentoOficio($dados);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function oficioPreview($contrato_id)
    {
        try {
            $contratoHistorico = Contratohistorico::where('contrato_id', $contrato_id)
                ->orderBy('id', 'desc')
                ->first();

            $contratoPublicacoes = ContratoPublicacoes::where('contratohistorico_id', $contratoHistorico->id)
                ->orderBy('id', 'desc')
                ->first();

            $this->enviaPublicacao($contratoHistorico, $contratoPublicacoes);

            dd('fim');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function oficioPreviewNovo()
    {
        $data = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));

        $contratoPublicacoes = ContratoPublicacoes::where('data_publicacao', $data->addDay())
            ->orderBy('id', 'desc')
            ->get();

        foreach ($contratoPublicacoes as $contratoPublicacao) {
            PublicaPreviewOficioJob::dispatch($contratoPublicacao)->onQueue('envia_preview_oficio');
        }
    }

    private function enviaPublicacao($contratoHistorico, $contratoPublicacoes)
    {
        $arrayPreview = $this->montaOficioPreview($contratoHistorico);
        $responsePreview = $this->soapClient->OficioPreview($arrayPreview);

        if (!isset($responsePreview->out->publicacaoPreview->DadosMateriaResponse->HASH)) {
            $contratoPublicacoes->status = 'Erro Preview!';
            $contratoPublicacoes->status_publicacao_id = $this->retornaIdTipoSituacao('DEVOLVIDO PELA IMPRENSA');
            $contratoPublicacoes->log = $this->retornaErroValidacoesDOU($responsePreview);
            $contratoPublicacoes->texto_dou = $this->retornaTextoModelo($contratoHistorico);
            $contratoPublicacoes->publicar = false;
            $contratoPublicacoes->save();

            return false;
        }

        $contratoPublicacoes->status = 'Preview';
        $contratoPublicacoes->texto_dou = $this->retornaTextoModelo($contratoHistorico);
        $contratoPublicacoes->save();

        $this->oficioConfirmacao($contratoHistorico, $contratoPublicacoes);

        return true;
    }

    public function retornaErroValidacoesDOU($responsePreview)
    {

        $erro = 'VERIFICAR : ';

        $erro .= ($responsePreview->out->validacaoCliente != "OK") ? 'CLENTE: ' . $responsePreview->out->validacaoCliente . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoDataPublicacao != "OK") ? 'DATA PUBLICACAO: ' . $responsePreview->out->validacaoDataPublicacao . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoIdentificadorNorma != "OK") ? 'IDENTIFICOR NORMA: ' . $responsePreview->out->validacaoIdentificadorNorma . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoIdentificadorTipoPagamento != "OK") ? 'TIPO PAGAMENTO: ' . $responsePreview->out->validacaoIdentificadorTipoPagamento . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoNUP != "OK") ? 'NUP: ' . $responsePreview->out->validacaoNUP . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoRTF != "OK") ? 'TEXTO RTF: ' . $responsePreview->out->validacaoRTF . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoSIORGCliente != "OK") ? 'SIORG CLIENT: ' . $responsePreview->out->validacaoSIORGCliente . ' | ' : '';
        $erro .= ($responsePreview->out->validacaoSIORGMateria != "OK") ? 'SIORG MATERIA: ' . $responsePreview->out->validacaoSIORGMateria . ' | ' : '';

        return $erro;
    }

    public function oficioConfirmacao(Contratohistorico $contratoHistorico, ContratoPublicacoes $contratoPublicacoes)
    {
        try {
            $arrayConfirmacao = $this->montaOficioConfirmacao($contratoHistorico);

            $responseConfirmacao = $this->soapClient->OficioConfirmacao($arrayConfirmacao);
            if (!isset($responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao)) {
                $contratoPublicacoes->status = 'Erro Ofício!';


                $contratoPublicacoes->status_publicacao_id = $this->retornaIdTipoSituacao('DEVOLVIDO PELA IMPRENSA');
                $contratoPublicacoes->log = json_encode($responseConfirmacao);
                $contratoPublicacoes->save();

                return false;
            }

            $contratoPublicacoes->status = 'Oficio';
            $contratoPublicacoes->status_publicacao_id = $this->retornaIdTipoSituacao('PUBLICADO');
            $contratoPublicacoes->transacao_id = $arrayConfirmacao['dados']['IDTransacao'];
            $contratoPublicacoes->materia_id = $responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDMateria;
            $contratoPublicacoes->oficio_id = $responseConfirmacao->out->publicacaoConfirmacao->DadosMateriaResponse->reciboConfirmacao->IDOficio;
            $contratoPublicacoes->save();

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function montaOficioPreview(Contratohistorico $contratoHistorico)
    {
        $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contratoHistorico->data_publicacao);
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contratoHistorico)['numero'];
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134;
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = config('publicacao.siorgmateria');
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;

        return $dados;
    }


    public function montaOficioConfirmacao(Contratohistorico $contratoHistorico)
    {
        $dados ['dados']['CPF'] = config('publicacao.usuario_publicacao');
        $dados ['dados']['IDTransacao'] = $contratoHistorico->unidade->nomeresumido . $this->generateRandonNumbers(13);
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contratoHistorico->data_publicacao);
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contratoHistorico)['numero'];
        $dados ['dados']['identificadorJornal'] = 3;
        $dados ['dados']['identificadorTipoPagamento'] = 149;
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = '';
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134;
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = config('publicacao.siorgmateria');
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;


        return $dados;
    }


    public function retornaNumeroEmpenho(Contratohistorico $contratoHistorico)
    {
        $retorno = [
            'numero'=> '',
            'texto'=> ''
        ];
        $contrato = $contratoHistorico->contrato;
        (!($contrato->empenhos->isEmpty())) ? $empenhos = $contrato->empenhos : $empenhos = [];

        $cont = count($empenhos);

        foreach ($empenhos as $key => $value) {
            $empenho = Empenho::find($value->empenho_id);
            if ($cont < 2) {
                $retorno['numero'] = $empenho->numero;
                $retorno['texto'] = " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
            if ($key == 0 && $cont > 1) {
                $retorno['numero'] = $empenho->numero . " - ";
                $retorno['texto'] = " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
            if ($key > 0 && $key < ($cont - 1)) {
                $retorno['numero'] .= $empenho->numero . " - ";
                $retorno['texto'] .= " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
            if ($key == ($cont - 1)) {
                $retorno['numero'] .= $empenho->numero;
                $retorno['texto'] .= " Fonte: " . $empenho->fonte . " - " . $empenho->numero;
            }
        }
        return $retorno;
    }


    public function retornaCabecalhoRtf()
    {
        $textoCabecalho = "{\\rtf1\ansi\ansicpg1252\deff0\\nouicompat\deflang1046\deflangfe1046\deftab708{\\fonttbl{\\f0\\fnil\\fcharset0 Calibri;}}
                    {\colortbl ;\\red0\green0\blue255;}
                    {\*\generator Riched20 10.0.17763}{\*\mmathPr\mdispDef1\mwrapIndent1440 }\\viewkind4\uc1 \pard\widctlpar\\f0\\fs18 \par ";
        return $textoCabecalho;
    }


    public function converteTextoParaRtf(string $TextoModelo)
    {
        $rtf = new PHPRtfLite();
        $section = $rtf->addSection();
        $font = new PHPRtfLite_Font(9, 'Calibri');
        $section->writeText($TextoModelo, $font);
        $texto = $rtf->getContent();

        return $texto;
    }


    public function retornaTextoRtf(Contratohistorico $contratoHistorico)
    {

        $textoCabecalho = $this->retornaCabecalhoRtf();

        $textomodelo = $this->retornaTextoModelo($contratoHistorico);
        $texto = $this->converteTextoParaRtf($textomodelo);
        $texto = $textoCabecalho . substr($texto, strripos($texto, '##ATO'));

        return $texto;
    }


    public function retornaTextoModelo(Contratohistorico $contratoHistorico)
    {

        switch ($contratoHistorico->getTipo()) {
            case "Contrato":
                $textomodelo = $this->retornaTextoModeloContrato($contratoHistorico);
                break;
            case "Termo Aditivo":
                $textomodelo = $this->retornaTextoModelorAditivo($contratoHistorico);
                break;
            case "Termo Apostilamento":
                $textomodelo = $this->retornaTextoModeloApostilamento($contratoHistorico);
                break;
            case "Termo de Rescisão":
                $textomodelo = $this->retornaTextoModeloRescisão($contratoHistorico);
                break;
        }
        return $textomodelo;
    }


    public function retornaTextoModeloContrato(Contratohistorico $contratoHistorico)
    {

        $contrato = $contratoHistorico->contrato;
        $TextoModelo = "##ATO EXTRATO DE CONTRATO Nº " . $contratoHistorico->numero . " - UASG " . $contratoHistorico->getUnidade() . "
        Nº Processo: " . $contrato->processo . ".
        ##TEX " . strtoupper($contrato->modalidade->descricao) . " SRP Nº " . $contrato->licitacao_numero . ". Contratante: " . $contrato->unidade->nome . ".
        Contratado: " . $contratoHistorico->fornecedor->cpf_cnpj_idgener . " - " . $contratoHistorico->fornecedor->nome . " -.
        Objeto: " . $contratoHistorico->objeto . ".
        Fundamento Legal: " . $contrato->retornaAmparo() . " . Vigência: " . $contratoHistorico->getVigenciaInicio() . " a " . $contratoHistorico->getVigenciaFim() .
            ". Valor Total: R$" . $contratoHistorico->getValorGlobal() . "." . $this->retornaNumeroEmpenho($contratoHistorico)['texto'] . ". Data de Assinatura: " . $this->retornaDataFormatada($contratoHistorico->data_assinatura) . ".";

        return $TextoModelo;
    }


    public function retornaTextoModelorAditivo(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;

        $textomodelo =
            "##ATO EXTRATO DE TERMO ADITIVO Nº ".$contratoHistorico->numero.
            " - UASG ".$contratoHistorico->getUnidade().
            " Número do Contrato: ".$contrato->numero.
            ". Nº Processo: ".$contrato->processo.".
            ##TEX ".strtoupper($contrato->modalidade->descricao).
            " Nº ".$contrato->licitacao_numero.
            ". Contratante: ".$contrato->unidade->nome.
            ". CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.
            ". Contratado : ".$contratoHistorico->fornecedor->nome.
            " -.Objeto: ".$contratoHistorico->objeto.
            " Fundamento Legal: ".$contrato->retornaAmparo().
            ". Vigência: ".$contratoHistorico->getVigenciaInicio().
            " a ".$contratoHistorico->getVigenciaFim().". ".$this->retornaNumeroEmpenho($contratoHistorico)['texto'].
            ". Data de Assinatura: " . $this->retornaDataFormatada($contratoHistorico->data_assinatura) . ".";
        return $textomodelo;

        return $textomodelo;
    }

    public function retornaTextoModeloApostilamento(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;

        $textomodelo =
            "##ATO EXTRATO DE TERMO APOSTILAMENTO Nº ".$contratoHistorico->numero.
            " - UASG ".$contratoHistorico->getUnidade().
            " Número do Contrato: ".$contrato->numero.
            ". Nº Processo: ".$contrato->processo.".
            ##TEX ".strtoupper($contrato->modalidade->descricao)." Nº ".$contrato->licitacao_numero.
            ". Contratante: ".$contrato->unidade->nome.
            ". CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.
            ". Contratado : ".$contratoHistorico->fornecedor->nome.
            " -.Objeto: ".$contratoHistorico->objeto.
            " Fundamento Legal: ".$contrato->retornaAmparo().
            ". Vigência: ".$contratoHistorico->getVigenciaInicio().
            " a ".$contratoHistorico->getVigenciaFim().". ".$this->retornaNumeroEmpenho($contratoHistorico)['texto'].
            ". Data de Assinatura: " . $this->retornaDataFormatada($contratoHistorico->data_assinatura) . ".";

        return $textomodelo;

        return $textomodelo;
    }

    public function retornaTextoModeloRescisão(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;

        $textomodelo =
            "##ATO EXTRATO DE TERMO DE RECISÃO Nº ".$contratoHistorico->numero.
            " - UASG ".$contratoHistorico->getUnidade().
            " Número do Contrato: ".$contrato->numero.
            ". Nº Processo: ".$contrato->processo.".
            ##TEX ".strtoupper($contrato->modalidade->descricao).
            " Nº ".$contrato->licitacao_numero.
            ". Contratante: ".$contrato->unidade->nome.
            ". CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.
            ". Contratado : ".$contratoHistorico->fornecedor->nome.
            " -.Objeto: ".$contratoHistorico->objeto.
            " Fundamento Legal: ".$contrato->retornaAmparo().
            ". Vigência: ".$contratoHistorico->getVigenciaInicio().
            " a ".$contratoHistorico->getVigenciaFim().
            ". ".$this->retornaNumeroEmpenho($contratoHistorico)['texto'].
            ". Data de Assinatura: " . $this->retornaDataFormatada($contratoHistorico->data_assinatura) . ".";
        return $textomodelo;

        return $textomodelo;
    }

    function generateRandonNumbers($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function executaJobAtualizaSituacaoPublicacao()
    {
        $model = new ContratoPublicacoes;
        $publicacoes = $model->retornaPublicacoesEnviadas();

        foreach ($publicacoes as $publicacao) {
            if (isset($publicacao->id)) {
                //AtualizaSituacaoPublicacaoJob::dispatch($publicacao)->onQueue('consulta_situacao_publicacao');
                $this->testaAtualizacaoStatusPublicacao($publicacao);
            }
        }
        dd('fim');
    }


    public function testaAtualizacaoStatusPublicacao($publicacao)
    {
        $retorno = $this->consultaSituacaoOficio($publicacao->oficio_id);
        if ($retorno->out->validacaoIdOficio == "OK") {
            $status = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->estadoMateria;
            if ($status != "PUBLICADA") {
                $tipoSituacao = 'TRANSFERIDO PARA IMPRENSA';
                $this->atualizaPublicacao($publicacao, $status, $tipoSituacao);
            } else {
                $tipoSituacao = 'PUBLICADO';
                $this->atualizaPublicacao($publicacao, $status, $tipoSituacao);
            }
        }
        dd('fim');
    }


    public function atualizaPublicacao($publicacao, $status, $tipoSituacao)
    {
        $publicacao->status_publicacao_id = $this->retornaIdTipoSituacao($tipoSituacao);
        $publicacao->status = $status;
        $publicacao->save();
    }

    public function retornaIdTipoSituacao($tipoSituacao)
    {
        return Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situacao Publicacao');
        })
            ->where('descricao', '=', $tipoSituacao)
            ->first()->id;
    }

    private function retornaDataFormatada($data)
    {
        $date = new \DateTime($data);
        return date_format($date, 'd/m/Y');
    }
}
