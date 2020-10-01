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

            $params = array(
                    'identificadorJornal' => '3'
            );

//            $response = $this->soapClient->ConsultaNormas($params);
              $response = $this->soapClient->ConsultaFormasPagamento(array('cpf' => '70074402153'));
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

        $dados ['dados']['CPF'] = $this->retornaCpfResponsavel($contrato); //SOLICITAR CPF CADASTRADO E ATIVO NO INCOM
        $dados ['dados']['UG'] = $contrato->unidade->codigo;
        $dados ['dados']['dataPublicacao'] = strtotime($contrato->data_publicacao);
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contrato);
        $dados ['dados']['identificadorJornal'] = 3; //Diário Oficial Seção - 2 -> ConsultaJornais
        $dados ['dados']['identificadorTipoPagamento'] = 149; //149 ISENTO -> ConsultaFormasPagamento //89 - empenho
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = ''; //Número único de Processo relacionado à publicação NÃO OBRIGATÓRIO
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($texto);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'] = 134; //ConsultaNormas -> 134 Edital de Citação
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'] = ''; //código siorg AGU
        $dados ['dados']['motivoIsencao'] = 9;
        $dados ['dados']['siorgCliente'] = 46;

        return $dados;

    }

    public function retornaCpfResponsavel(Contrato $contrato)
    {
        $cpf = BackpackUser::find($contrato->responsaveis[0]['user_id'])->cpf;

        if(is_null($cpf)){
            return false;
        } 
        return preg_replace('/[^0-9]/', '', $cpf);
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

        $file = fopen(env('DOU_CONTRATOS'), "r");

        while (!feof($file)) {
            $line = fgets($file);
            $texto = $texto.$line;
        }
        fclose($file);

        dump($texto);

        return $texto;
    }

}

