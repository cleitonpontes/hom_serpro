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

            //$this->soapClient = InstanceSoapClient::init($headers);
    }

    public function consultaTodosFeriado(){
        try {

            $response = $this->soapClient->ConsultaTodosFeriado();

            dd($response);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function oficioPreview($contrato_id){
        try {
            $contrato = Contrato::find($contrato_id)->first();

            $empenho = $this->montaOficioPreview($contrato);

            dd($empenho);
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
        $dados ['dados']['dataPublicacao'] = $contrato->data_publicacao;
        $dados ['dados']['empenho'] = $this->retornaNumeroEmpenho($contrato);
        $dados ['dados']['identificadorJornal'] = 1; //Diário Oficial Seção - 1 -> ConsultaJornais
        $dados ['dados']['identificadorTipoPagamento'] = 149; //ISENTO -> ConsultaFormasPagamento
        $dados ['dados']['materia']['DadosMateriaRequest']['NUP'] = ''; //Número único de Processo relacionado à publicação NÃO OBRIGATÓRIO
        $dados ['dados']['materia']['DadosMateriaRequest']['conteudo'] = $this->retornaTextoRtf($texto);
        $dados ['dados']['materia']['DadosMateriaRequest']['identificadorNorma'];
        $dados ['dados']['materia']['DadosMateriaRequest']['siorgMateria'];
        $dados ['dados']['motivoIsencao'];
        $dados ['dados']['siorgCliente'];

        return $dados;

    }

    public function retornaCpfResponsavel(Contrato $contrato)
    {
        return preg_replace('/[^0-9]/', '', BackpackUser::find($contrato->responsaveis[0]['user_id'])->cpf);
    }

    public function retornaNumeroEmpenho(Contrato $contrato)
    {
        return Empenho::find($contrato->empenhos[0]['empenho_id'])->numero;
    }

    public function retornaTextoRtf(string $texto)
    {
        $texto = "Lorem Ipsum is simply dummy text of the printing
                  and typesetting industry. Lorem Ipsum has been the
                  industry's standard dummy text ever since the 1500s,
                  when an unknown printer took a galley of type and
                  scrambled it to make a type specimen book. It has
                  survived not only five centuries, but also the leap
                  into electronic typesetting, remaining essentially
                  unchanged. It was popularised in the 1960s with the
                  release of Letraset sheets containing Lorem Ipsum
                  passages, and more recently with desktop publishing
                  software like Aldus PageMaker including versions of
                  Lorem Ipsum.";

        return $texto;
    }

}
//
//<xfir:OficioPreview>
// <xfir:dados>
// <data:CPF>?</data:CPF> <!-- Texto(formato –99999999999) -->
// <data:UG>?</data:UG> <!-- Inteiro(10) -->
// <data:dataPublicacao>?</data:dataPublicacao> <!--Date -->
// <data:empenho>?</data:empenho> <!--Texto-->
// <data:identificadorJornal>?</data:identificadorJornal> <!--Inteiro (10) -->
// <data:identificadorTipoPagamento>?</data:identificadorTipoPagamento> <!--Inteiro (10) -

// <data:materia>
    // <!--Zero or more repetitions:-->
    // <data:DadosMateriaRequest>
        // <data:NUP>?</data:NUP> <!-- Texto(formato –99999999999) -->
        // <data:conteudo>?</data:conteudo> <!--Texto -->
        // <data:identificadorNorma>?</data:identificadorNorma> <!--Inteiro (10) -->
        // <data:siorgMateria>?</data:siorgMateria> <!--Inteiro (10) -->
    // </data:DadosMateriaRequest>
// </data:materia> <!--Lista de Matéria -->
// <data:motivoIsencao>?</data:motivoIsencao> <!--Inteiro (10) -->
// <data:siorgCliente>?</data:siorgCliente>
// </xfir:dados>
// </xfir:OficioPreview>
