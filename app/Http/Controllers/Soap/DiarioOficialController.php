<?php

namespace App\Http\Controllers\Soap;

use App\Models\BackpackUser;
use App\Models\Contrato;
use App\Models\Contratoempenho;
use App\Models\Empenho;
use SoapHeader;
use SoapVar;

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

            $response = $this->soapClient->__getTypes();

            dd($response);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function oficioPreview($contrato_id){
        try {

            $contrato = Contrato::find($contrato_id)->first();

            $arrayPreview = $this->montaOficioPreview($contrato);

            $response = $this->soapClient->OficioPreview($arrayPreview);

            dd($response);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function montaOficioPreview(Contrato $contrato)
    {

        $texto = "";

        $dados ['dados']['CPF'] = $this->retornaCpfResponsavel($contrato);
        $dados ['dados']['UG'] = $contrato->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contrato->data_publicacao);
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contrato);
        $dados ['dados']['identificadorJornal'] = 1; //Diário Oficial Seção - 1 -> ConsultaJornais
        $dados ['dados']['identificadorTipoPagamento'] = 149; //ISENTO -> ConsultaFormasPagamento
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = ''; //Número único de Processo relacionado à publicação NÃO OBRIGATÓRIO
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($texto);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134; //ConsultaNormas -> 134 Edital de Citação
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = 46; //código siorg AGU
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = 46;

        return $dados;

    }

    public function retornaCpfResponsavel(Contrato $contrato)
    {
        return preg_replace('/[^0-9]/', '', BackpackUser::find($contrato->responsaveis[0]['user_id'])->cpf);
    }

    public function retornaNumeroEmpenho(Contrato $contrato)
    {

        $empenho = Empenho::find($contrato->empenhos[0]['empenho_id'])->numero;

        if(!is_null($empenho))
            return $empenho;
        return '';
    }

    public function retornaTextoRtf(string $texto)
    {
        $texto = "{\rtf1\ansi\ansicpg1252\deff0\nouicompat\deflang1046{\fonttbl{\f0\fnil\fcharset0 Calibri;}}
                    {\*\generator Riched20 10.0.18362}\viewkind4\uc1
                    \pard\sa200\sl276\slmult1\qc\b\f0\fs22\lang22 EXTRATO DE CONTRATO N\'ba 14/2020 - UASG 110099\par

                    \pard\sa200\sl276\slmult1\b0\par
                    \tab N\'ba Processo: 00589000389202081.\par
                    \par

                    \pard\sa200\sl276\slmult1\qj\tab PREG\'c3O SRP N\'ba 5/2019. Contratante: SUPERINTENDENCIA DE ADMINISTRACAO-NO ESTADO DE SAO PAUL. CNPJ Contratado: 18083458000117. Contratado : ARCOM COMERCIO E SERVICOS EIRELI -.Objeto: Contrata\'e7\'e3o de servi\'e7os de manuten\'e7\'e3o predial com e sem dedica\'e7\'e3o exclusiva de m\'e3o de obra para as Unidades da AGU em S\'e3o Paulo, Osasco e Guarulhos conforme edital e seus anexos. Fundamento Legal: Lei 10520/2002, Lei 8666/93, Decreto 7982/2013, Decreto 7892/2013 e Decreto 9507/2018, In 05/2017 . Vig\'eancia: 01/07/2020 a 01/07/2021. Valor Total: R$776.446,86. Fonte: 100000000 - 2020NE800828 Fonte: 100000000 - 2020 800829. Data de Assinatura: 19/06/2020.\par

                    \pard\sa200\sl276\slmult1\par
                    (SICON - 10/09/2020)\par
                    \par
                    }";

//        $texto = "Nas linhas 19 a 24 se extrai o corpo do documento RTF, o corpo do documento é o que se repete, para extraí-lo primeiro obtemos o cabeçalho do documento, o cabeçalho fica determinado pela etiqueta rtf sectd. Um documento rtf termina sempre em }, com essa informação extraímos o corpo.";

        return $texto;
    }

}

