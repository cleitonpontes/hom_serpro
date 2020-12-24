<?php

namespace App\Http\Controllers\Publicacao;

use Alert;
use App\Http\Traits\BuscaCodigoItens;
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

    use BuscaCodigoItens;

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
            $contratoPublicacoes->texto_dou = $this->retornaTextoRtf($contratoHistorico);
            //$contratoPublicacoes->publicar = false;
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
        $data = date('d/m/Y');

        //todo buscar do banco e atualizar o modelo
        $padraoPublicacaoContrato = '##ATO EXTRATO DE CONTRATO Nº |contratoHistorico_numero| - UASG |contratoHistorico_unidade|';

        $contrato = $contratoHistorico->contrato;
        $TextoModelo = "##ATO EXTRATO DE CONTRATO Nº " . $contratoHistorico->numero . " - UASG " . $contratoHistorico->getUnidade() . "
            Nº Processo: " . $contrato->processo . ".
            ##TEX " . strtoupper($contrato->modalidade->descricao) . " SRP Nº " . $contrato->licitacao_numero . ". Contratante: " . $contrato->unidade->nome . ".
            Contratado: " . $contratoHistorico->fornecedor->cpf_cnpj_idgener . " - " . $contratoHistorico->fornecedor->nome . " -.
            Objeto: " . $contratoHistorico->objeto . ".
            Fundamento Legal: " . $contrato->retornaAmparo() .
            " . Vigência: " . $contratoHistorico->getVigenciaInicio() . " a " . $contratoHistorico->getVigenciaFim() .
            ". Valor Total: R$" . $contratoHistorico->getValorGlobal() . "." . $this->retornaNumeroEmpenho($contratoHistorico)['texto'] .
            ". Data de Assinatura: " . $this->retornaDataFormatada($contratoHistorico->data_assinatura) . ".".
            "##ASS COMPRASNET 4.0 - " . date_format(new \DateTime(), 'd/m/Y') . ".";

        $padraoPublicacaoContrato = str_replace('|contratoHistorico_numero|', $contratoHistorico->numero, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_getUnidade|', $contratoHistorico->getUnidade(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contrato_processo|', $contrato->processo, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contrato_modalidade_descricao|', $contrato->modalidade->descricao, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contrato_licitacao_numero|', $contrato->licitacao_numero, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contrato_unidade_nome|', $contrato->unidade->nome, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_fornecedor_cpf_cnpj_idgener|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_fornecedor_nome|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_objeto|', $contratoHistorico->objeto, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contrato_retornaAmparo|', $contrato->retornaAmparo(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_getVigenciaInicio|', $contratoHistorico->getVigenciaInicio(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_getVigenciaFim|', $contratoHistorico->getVigenciaFim(), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_valor_global|', $contratoHistorico->valor_global, $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|numero_empenho|', $this->retornaNumeroEmpenho($contratoHistorico)['texto'], $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|contratoHistorico_data_assinatura|', $this->retornaDataFormatada($contratoHistorico->data_assinatura), $padraoPublicacaoContrato);
        $padraoPublicacaoContrato = str_replace('|data_assinatura_sistema|', date_format(new \DateTime(), 'd/m/Y'), $padraoPublicacaoContrato);

        return $padraoPublicacaoContrato;
    }

    public function retornaTextoModelorAditivo(Contratohistorico $contratoHistorico)
    {
        //todo buscar do banco
        $padraoPublicacaoAditivo = '##ATO EXTRATO DE TERMO ADITIVO Nº |contratoHistorico_numero| - UASG |contratoHistorico_getUnidade| Número do Contrato: |contrato_numero|. Nº Processo: |contrato_processo|.

        ##TEX |contrato_modalidade_descricao|. Nº |contrato_licitacao_numero|. Contratante: |contrato_unidade_nome|. CNPJ Contratado: |contratoHistorico_fornecedor_cpf_cnpj_idgener|. Contratado : |contratoHistorico_fornecedor_nome|. Objeto: |contratoHistorico_objeto|. Fundamento Legal: |contrato_retornaAmparo|. Vigência: |contratoHistorico_getVigenciaInicio| a |contratoHistorico_getVigenciaFim|. |numero_empenho|. Valor Total: |contratoHistorico_valor_global|. Data de Assinatura: |contratoHistorico_data_assinatura.

        ##ASS COMPRASNET 4.0 - |data_assinatura_sistema|.';

        $data = date('d/m/Y');
        $contrato = $contratoHistorico->contrato;

        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_numero|', $contratoHistorico->numero, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_getUnidade|', $contratoHistorico->getUnidade(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_numero|', $contrato->numero, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_processo|', $contrato->processo, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_modalidade_descricao|', $contrato->modalidade->descricao, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_licitacao_numero|', $contrato->licitacao_numero, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_unidade_nome|', $contrato->unidade->nome, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_fornecedor_cpf_cnpj_idgener|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_fornecedor_nome|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_objeto|', $contratoHistorico->objeto, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_retornaAmparo|', $contrato->retornaAmparo(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_getVigenciaInicio|', $contratoHistorico->getVigenciaInicio(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_getVigenciaFim|', $contratoHistorico->getVigenciaFim(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|numero_empenho|', $this->retornaNumeroEmpenho($contratoHistorico)['texto'], $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_valor_global|', $contratoHistorico->valor_global, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_data_assinatura|', $this->retornaDataFormatada($contratoHistorico->data_assinatura), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|data_assinatura_sistema|',$data, $padraoPublicacaoAditivo);


        return $padraoPublicacaoAditivo;
    }

    public function retornaTextoModeloApostilamento(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');
        //todo buscar do banco
        $padraoPublicacaoAditivo = '##ATO EXTRATO DE TERMO APOSTILAMENTO.

        ##TEX |contratoHistorico_objeto|.

        ##ASS COMPRASNET 4.0 - |contratoHistorico_data_assinatura|.';
        $padraoPublicacaoApostilamento = '##ATO EXTRATO DE TERMO APOSTILAMENTO. ##TEX |contratoHistorico_objeto|. ##ASS COMPRASNET 4.0 - |contratoHistorico_data_assinatura|.';

        $padraoPublicacaoApostilamento = str_replace('|contratoHistorico_objeto|', $contratoHistorico->objeto, $padraoPublicacaoApostilamento);
        $padraoPublicacaoApostilamento = str_replace('|data_assinatura_sistema|', date_format(new \DateTime(), 'd/m/Y'), $padraoPublicacaoApostilamento);

        return $padraoPublicacaoApostilamento;
    }

    public function retornaTextoModeloRescisão(Contratohistorico $contratoHistorico)
    {
        $data = date('d/m/Y');
        //todo buscar do banco
        $padraoPublicacaoAditivo = '##ATO EXTRATO DE TERMO DE RECISÃO Nº |contratoHistorico_numero|.

        ##TEX Nº Processo: |contrato_processo|. Contratante: |contrato_unidade_nome|. CNPJ Contratado: |contratoHistorico_fornecedor_cpf_cnpj_idgener|. Contratado : |contratoHistorico_fornecedor_nome|. Objeto: |contratoHistorico_objeto|. Fundamento Legal: |contrato_retornaAmparo|. Data de Rescisão: |contratoHistorico_data_assinatura|.

        ##ASS COMPRASNET 4.0 - |data_assinatura_sistema|.';


        $contrato = $contratoHistorico->contrato;

        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_numero|', $contratoHistorico->numero, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_processo|', $contrato->processo, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_unidade_nome|', $contrato->unidade->nome, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_fornecedor_cpf_cnpj_idgener|', $contratoHistorico->fornecedor->cpf_cnpj_idgener, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_fornecedor_nome|', $contratoHistorico->fornecedor->nome, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_objeto|', $contratoHistorico->objeto, $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contrato_retornaAmparo|', $contrato->retornaAmparo(), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|contratoHistorico_data_assinatura|', $this->retornaDataFormatada($contratoHistorico->data_assinatura), $padraoPublicacaoAditivo);
        $padraoPublicacaoAditivo = str_replace('|data_assinatura_sistema|', $data, 'd/m/Y'), $padraoPublicacaoAditivo);

        return $padraoPublicacaoRecisao;
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
                $this->atualizaPublicacao($publicacao, $retorno, $tipoSituacao);
            } else {
                $tipoSituacao = 'PUBLICADO';
                $this->atualizaPublicacao($publicacao, $retorno, $tipoSituacao);
            }
        }
        dd('fim');
    }


    public function atualizaPublicacao($publicacao, $retorno, $tipoSituacao)
    {
        $link = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->linkPublicacao;
        $pagina = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->paginaPublicacao;
        $motivo_devolucao = $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->motivoDevolucao;
        $codigo = 'Situacao Publicacao';

        $publicacao->status_publicacao_id = $this->retornaIdCodigoItem($codigo,$tipoSituacao);
        $publicacao->status =  $retorno->out->acompanhamentoOficio->acompanhamentoMateria->DadosAcompanhamentoMateria->estadoMateria;
        $publicacao->link_publicacao = $link;
        $publicacao->pagina_publicacao = $pagina;
        $publicacao->motivo_devolucao = $motivo_devolucao;
        $publicacao->secao_jornal = 3;

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







    public function retornaTextoretificacao(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;
        $publicacao = $contratoHistorico->publicacao;

        $textomodelo =
            "##ATO RETIFICAÇÃO
            ##TEX No Extrato de {{TIPO CONTRATO}} Nº ".$contratoHistorico->numero.
            " publicado no D.O de ".$this->retornaDataFormatada($contratoHistorico->data_publicacao).", ".
            "Seção 3, Pág.".$publicacao->pagina_publicacao.". Onde se lê: ";

        $alteracao = "";

//            ". Contratante: ".$contrato->unidade->nome.
//            ". CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.
//            ". Contratado : ".$contratoHistorico->fornecedor->nome.
//            " .Objeto: ".$contratoHistorico->objeto.
//            " Fundamento Legal: ".$contrato->retornaAmparo().
//            ". Vigência: ".$contratoHistorico->getVigenciaInicio().
//            " a ".$contratoHistorico->getVigenciaFim().". ".$this->retornaNumeroEmpenho($contratoHistorico)['texto'].
//            ". Valor Total: " . $contratoHistorico->valor_global .
//            ". Data de Assinatura: " . $this->retornaDataFormatada($contratoHistorico->data_assinatura)  . ".
//            ##ASS COMPRASNET 4.0 - " . date_format(new \DateTime(), 'd/m/Y') . ".";

        //##ATO EXTRATO DE TERMO ADITIVO Nº |contratoHistorico_numero| - UASG |contratoHistorico_getUnidade| Número do Contrato: |contrato_numero|. Nº Processo: |contrato_processo|. ##TEX |contrato_modalidade_descricao|. Nº |contrato_licitacao_numero|. Contratante: |contrato_unidade_nome|. CNPJ Contratado: |contratoHistorico_fornecedor_cpf_cnpj_idgener|. Contratado : |contratoHistorico_fornecedor_nome|. Objeto: |contratoHistorico_objeto|. Fundamento Legal: |contrato_retornaAmparo|. Vigência: |contratoHistorico_getVigenciaInicio| a |contratoHistorico_getVigenciaFim|. |numero_empenho|. Valor Total: |contratoHistorico_valor_global|. Data de Assinatura: |contratoHistorico_data_assinatura|. ##ASS COMPRASNET 4.0 - |contratoHistorico_data_assinatura|.
        return $textomodelo;
    }

}
