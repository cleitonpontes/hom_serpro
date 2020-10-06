<?php

namespace App\Http\Controllers\Soap;

use App\Models\BackpackUser;
use App\Models\Contrato;
use App\Models\Contratoempenho;
use App\Models\Contratohistorico;
use App\Models\Empenho;
use SoapHeader;
use SoapVar;
use PHPRtfLite;
use PHPRtfLite_Font;


class DiarioOficialController extends BaseSoapController
{
    private $soapClient;
    private $securityNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private $Urlwsdl = 'https://homologwsincom.in.gov.br/services/servicoIN?wsdl';
    private $username = 'andre.castro';
    private $password = 'acesso123';


    public function __construct()
    {
        self::setWsdl($this->Urlwsdl);
        $node1 = new SoapVar($this->username, XSD_STRING, null, null, 'Username', $this->securityNS);
        $node2 = new SoapVar($this->password, XSD_STRING, null, null, 'Password', $this->securityNS);
        $token = new SoapVar(array($node1,$node2), SOAP_ENC_OBJECT, null, null, 'UsernameToken', $this->securityNS);
        $security = new SoapVar(array($token), SOAP_ENC_OBJECT, null, null, 'Security', $this->securityNS);
        $headers[] = new SOAPHeader($this->securityNS, 'Security', $security, false);

        $this->soapClient = InstanceSoapClient::init($headers);
    }

    public function consultaTodosFeriado(){
        try {

            $params = array(
                    'identificadorJornal' => '3'
            );
            $response = $this->soapClient->ConsultaTodosOrgaosPermitidos();
//            $response = $this->soapClient->ConsultaNormas($params);
              //$response = $this->soapClient->ConsultaFormasPagamento(array('cpf' => '01895591111'));
//            $response = $this->soapClient->ConsultaTodosMotivosIsencao();
            dd($response);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function oficioPreview($contrato_id){
        try {

            $contrato = Contrato::find($contrato_id)->first();
//            dd($contrato);
            $contratoHistorico = $contrato->historico->last();

            $arrayPreview = $this->montaOficioPreview($contratoHistorico);

            $response = $this->soapClient->OficioPreview($arrayPreview);
            dd($response);
//            dd($this->soapClient->__getLastRequest());
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function montaOficioPreview(Contratohistorico $contratoHistorico)
    {

        $dados ['dados']['CPF'] = '01895591111';//usuário cadastrado no Incom
        $dados ['dados']['UG'] = $contratoHistorico->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contratoHistorico->data_publicacao);
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contratoHistorico);
        $dados ['dados']['identificadorJornal'] = 3; //Diário Oficial Seção - 2 -> ConsultaJornais
        $dados ['dados']['identificadorTipoPagamento'] = 149; //149 ISENTO -> ConsultaFormasPagamento //89 - empenho
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = ''; //Número único de Processo relacionado à publicação NÃO OBRIGATÓRIO
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($contratoHistorico);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134; //ConsultaNormas -> 134 Edital de Citação
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = $contratoHistorico->unidade->codigo_siorg; //código siorg AGU
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = $contratoHistorico->unidade->codigo_siorg;

        return $dados;

    }

    public function retornaNumeroEmpenho(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;
        $empenho = Empenho::find($contrato->empenhos[0]['empenho_id'])->numero;

        if(!is_null($empenho))
            return $empenho;
        return '';
    }

    public function retornaTextoRtf(Contratohistorico $contratoHistorico)
    {
        $texto = "";
        switch ($contratoHistorico->getTipo()){
            case "Contrato":
                $texto = $this->retornaTextoContrato($contratoHistorico);
                break;
            case "Termo Aditivo":
                $texto = $this->retornaTextoAditivo($contratoHistorico);
                break;
        }
        return $texto;
    }

    public function retornaTextoContrato(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;
        $textoCabecalho = "{\\rtf1\ansi\ansicpg1252\deff0\\nouicompat\deflang1046\deflangfe1046\deftab708{\\fonttbl{\\f0\\fnil\\fcharset0 Calibri;}}
                    {\colortbl ;\\red0\green0\blue255;}
                    {\*\generator Riched20 10.0.17763}{\*\mmathPr\mdispDef1\mwrapIndent1440 }\\viewkind4\uc1 \pard\widctlpar\\f0\\fs18 \par";


        $TextoModelo = "##ATO EXTRATO DE CONTRATO Nº ".$contratoHistorico->numero." - UASG ".$contratoHistorico->getUnidade()."
        Nº Processo: ".$contrato->processo.".
        ##TEX ".strtoupper($contrato->modalidade->descricao)." SRP Nº ".$contrato->licitacao_numero.". Contratante: ".$contrato->unidade->nome.".
        CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.". Contratado : ".$contratoHistorico->fornecedor->nome." -.
        Objeto: ".$contratoHistorico->objeto.".
        Fundamento Legal: Lei 10520/2002, Lei 8666/93, Decreto 7982/2013, Decreto 7892/2013 e Decreto
        9507/2018, In 05/2017 . Vigência: 01/07/2020 a 01/07/2021. Valor Total: R$776.446,86. Fonte:
        100000000 - 2020NE800828 Fonte: 100000000 - 2020 800829. Data de Assinatura: 19/06/2020.";

        $rtf = new PHPRtfLite();
        $section =  $rtf->addSection();
        $font = new PHPRtfLite_Font(9,'Calibri');
        $parFormat = new \PHPRtfLite_ParFormat();
        $section->writeText($TextoModelo, $font);
        $texto = $rtf->getContent();
        $texto = $textoCabecalho.substr($texto,strripos($texto, '##ATO'));

        return $texto;
    }

    public function retornaTextoAditivo(Contratohistorico $contratoHistorico)
    {
        $contrato = $contratoHistorico->contrato;
        $textoCabecalho = "{\\rtf1\ansi\ansicpg1252\deff0\\nouicompat\deflang1046\deflangfe1046\deftab708{\\fonttbl{\\f0\\fnil\\fcharset0 Calibri;}}
                    {\colortbl ;\\red0\green0\blue255;}
                    {\*\generator Riched20 10.0.17763}{\*\mmathPr\mdispDef1\mwrapIndent1440 }\\viewkind4\uc1 \pard\widctlpar\\f0\\fs18 \par";

        $TextoModelo = "##ATO EXTRATO DE TERMO ADITIVO Nº ".$contratoHistorico->numero." - UASG ".$contratoHistorico->getUnidade()." Número do Contrato: ".$contrato->numero.". Nº Processo: ".$contrato->processo.".
                        ##TEX ".strtoupper($contrato->modalidade->descricao)." Nº ".$contrato->licitacao_numero.". Contratante: ".$contrato->unidade->nome.". CNPJ Contratado: ".$contratoHistorico->fornecedor->cpf_cnpj_idgener.". Contratado : ".$contratoHistorico->fornecedor->nome." -.Objeto: ".$contratoHistorico->objeto." Fundamento Legal: Art. 65,I, da Lei nº 8.666/93. Vigência: ".$contratoHistorico->getVigenciaInicio()." a ".$contratoHistorico->getVigenciaFim().". Fonte: 100000000 - 2019NE800903. Data de Assinatura: 01/04/2020.";

        $rtf = new PHPRtfLite();
        $section =  $rtf->addSection();
        $font = new PHPRtfLite_Font(9,'Calibri');
        $section->writeText($TextoModelo, $font);
        $texto = $rtf->getContent();
        $texto = $textoCabecalho.substr($texto,strripos($texto, '##ATO'));

        return $texto;
    }

}

