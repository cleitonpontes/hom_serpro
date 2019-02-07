<?php

namespace App\XML;

use App\Models\SfCertificado;
use App\Models\SfNonce;

class Execsiafi
{
    public $resultado = [];

    protected function conexao_xml($user, $pass, $ug, $sf_id, $amb, $exercicio, $wsdl){

        if($amb == 'PROD')
        {
            //ambiente produção
            if($wsdl == 'CONSULTA')
            {
                $wsdl       = 'https://servicos-siafi.tesouro.gov.br/siafi'.$exercicio.'/services/tabelas/consultarTabelasAdministrativas?wsdl';
            }
            if($wsdl == 'CPR')
            {
                $wsdl       = 'https://servicos-siafi.tesouro.gov.br/siafi'.$exercicio.'/services/cpr/manterContasPagarReceber?wsdl';
            }
        }

        if($amb == 'HOM')
        {
            //ambiente homologação
            if($wsdl == 'CONSULTA')
            {
                $wsdl       = 'https://homextservicos-siafi.tesouro.gov.br/siafi'.$exercicio.'he/services/tabelas/consultarTabelasAdministrativas?wsdl';
            }
            if($wsdl == 'CPR')
            {
                $wsdl       = 'https://homextservicos-siafi.tesouro.gov.br/siafi'.$exercicio.'he/services/cpr/manterContasPagarReceber?wsdl';
            }

        }


        $certificado = SfCertificado::where('situacao', '=', 1)->orderBy('id','desc')->first();

        $dado = null;
        foreach ($certificado->chaveprivada as $c){
            $dado = explode('/',$c);
        }
        $chave = $dado[0][2];

        $dado = null;
        foreach ($certificado->certificado as $c){
            $dado = explode('/',$c);
        }
        $cert = $dado[0][2];

        $chave = "125133201902065c5ad85573449.txt";
        $cert = "125133201902065c5ad8556ab5a.txt";

        //certificado
        $key = env('APP_PATH').env('APP_PATH_CERT'). $chave;
        $crtkey    = env('APP_PATH').env('APP_PATH_CERT'). $cert;


        $context = stream_context_create([
            'ssl' => [
                'local_cert' => $crtkey,
                'local_pk'   => $key,
                'verify_peer' => false,
            ]]);

        $client = new \SoapClient($wsdl, [
            'trace'=>1,
            'stream_context' => $context,
        ]);

        $client->__setSoapHeaders(array($this->wssecurity($user, $pass),$this->cabecalho($ug,$sf_id)));

        return $client;

    }

    protected function cabecalho($ug, $sf_id)
    {

        $nonce = SfNonce::select()->orderBy('id', 'desc')->first();

        $nonce_id = $nonce->id + 1;

        $data = [
            'sf_id' => $sf_id,
            'tipo' => $ug."_".$nonce_id."_".$sf_id,
        ];

        if($sf_id=='')
        {
            unset($data['sf_id']);
        }

        SfNonce::create($data);

        $xml='<ns1:cabecalhoSIAFI><ug>'.$ug.'</ug><bilhetador><nonce>'.$nonce_id.'</nonce></bilhetador></ns1:cabecalhoSIAFI>';
        $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
            'Security',
            new \SoapVar($xml, XSD_ANYXML),
            true
        );

        return $header;

    }

    protected function wssecurity($user, $password)
    {
        // Creating date using yyyy-mm-ddThh:mm:ssZ format
        $tm_created = gmdate('Y-m-d\TH:i:s\Z');
        $tm_expires = gmdate('Y-m-d\TH:i:s\Z', gmdate('U') + 180); //only necessary if using the timestamp element

        // Generating and encoding a random number
        //$simple_nonce = mt_rand();
        //$encoded_nonce = base64_encode($simple_nonce);

        // Compiling WSS string
        //$passdigest = base64_encode(sha1($simple_nonce . $tm_created . $password, true));

        // Initializing namespaces
        $ns_wsse = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
        $ns_wsu = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
        $password_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText';
        //$encoding_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary';

        // Creating WSS identification header using SimpleXML
        $root = new \SimpleXMLElement('<root/>');

        $security = $root->addChild('wsse:Security', null, $ns_wsse);

        //the timestamp element is not required by all servers
        //$timestamp = $security->addChild('wsu:Timestamp', null, $ns_wsu);
        //$timestamp->addAttribute('wsu:Id', 'Timestamp-28');
        //$timestamp->addChild('wsu:Created', $tm_created, $ns_wsu);
        //$timestamp->addChild('wsu:Expires', $tm_expires, $ns_wsu);

        $usernameToken = $security->addChild('wsse:UsernameToken', null, $ns_wsse);
        $usernameToken->addChild('wsse:Username', $user, $ns_wsse);
        $usernameToken->addChild('wsse:Password', $password, $ns_wsse)->addAttribute('Type', $password_type);
        //$usernameToken->addChild('wsse:Nonce', $encoded_nonce, $ns_wsse);
        //$usernameToken->addChild('wsu:Created', $tm_created, $ns_wsu);

        // Recovering XML value from that object
        $root->registerXPathNamespace('wsse', $ns_wsse);
        $full = $root->xpath('/root/wsse:Security');
        $auth = $full[0]->asXML();

        return new \SoapHeader($ns_wsse, 'Security', new \SoapVar($auth, XSD_ANYXML), true);

    }

    protected function submit($client, $parms, $tipo)
    {
        try{

            if($tipo=='CONUG')
            {
                $client->tabConsultarUnidadeGestora($parms);
            }

            if($tipo=='CONRAZAO')
            {

                $client->TabConsultarSaldoContabil($parms);
            }

            if($tipo=='INCDH')
            {
                $client->cprDHCadastrarDocumentoHabil($parms);
            }

            if($tipo=='CANDH')
            {
                $client->cprDHCancelarDH($parms);
            }

            if($tipo=='CONSIT')
            {
                $client->cprDAConsultarSituacao($parms);
            }

            if($tipo=='CONDH')
            {
                $client->cprDHDetalharDH($parms);
            }

        } catch(\Exception $e) {

            //var_dump($e);

        }

        return $client->__getLastResponse();

    }

    public function conrazao($ug_user, $amb, $ano, $ug, $contacontabil, $contacorrente, $mesref){

        $cpf = str_replace('-','',str_replace('.','',backpack_user()->cpf));
        
        // dd($cpf, \Auth::user()->passwordsiafi);
        
        $senha = '';
        if(backpack_user()->senhasiafi){

            $senha = base64_decode(backpack_user()->senhasiafi);

        }else{

            \Alert::error('Cadastre sua Senha SIAFI em "Meus Dados"!')->flash();
//            \toast()->error('Cadastre sua Senha SIAFI em "Meus Dados"!', 'Erro');

//            return redirect()->route('inicio');

        }


        $client = $this->conexao_xml($cpf, $senha,$ug_user,'',$amb,$ano,'CONSULTA');

        $parms = new \stdClass;
        $parms->tabConsultarSaldo = ['codUG' => $ug,
            'contaContabil' => $contacontabil,
            'contaCorrente' => $contacorrente,
            'mesRefSaldo' => $mesref
        ];

        $retorno = $this->submit($client, $parms, 'CONRAZAO');

        return $this->trataretorno($retorno);


    }

    protected function trataretorno($retorno){

        $xml = simplexml_load_string(str_replace(':','',$retorno));

        $resultado = [];

        foreach($xml->soapHeader as $var2){

            foreach($var2->ns2EfetivacaoOperacao as $var3){

                $this->resultado[0] = $var3->resultado;

                if($this->resultado[0] == 'SUCESSO'){

                    foreach($xml->soapBody as $var4){

                        if(isset($var4->ns3cprDHCadastrarDocumentoHabilResponse)){

                            foreach($var4->ns3cprDHCadastrarDocumentoHabilResponse as $var5){

                                foreach($var5->CprDhResposta as $var6){

                                    $this->resultado[1] = $var6->numDH;
                                    $this->resultado[2] = $var6->numNs;

                                }

                            }

                        }

                        if(isset($var4->ns3tabConsultarSaldoContabilResponse)){

                            foreach($var4->ns3tabConsultarSaldoContabilResponse as $var5){

                                foreach($var5->saldoContabilInfo as $var6){

                                    $this->resultado[1] = $var6->codUG;
                                    $this->resultado[2] = $var6->contaContabil;
                                    $this->resultado[3] = $var6->contaCorrente;
                                    $this->resultado[4] = $var6->vlrSaldo;
                                    $this->resultado[5] = $var6->tipoSaldo;

                                }

                            }

                        }

                    }

                }

                if($this->resultado[0] == 'FALHA'){

                    foreach($xml->soapBody as $var4){

                        if(isset($var4->ns3cprDHCadastrarDocumentoHabilResponse)){

                            foreach($var4->ns3cprDHCadastrarDocumentoHabilResponse as $var5){

                                foreach($var5->CprDhResposta as $var6){

                                    if(isset($var6->mensagem)){

                                        $this->resultado[1]= 0;

                                        foreach($var6->mensagem as $var7){

                                            $this->resultado[2].= " | ".str_replace('"','',str_replace("'","",$var7->txtMsg));

                                        }

                                    }

                                }

                            }

                        }

                        if(isset($var4->soapFault)){

                            foreach($var4->soapFault as $var5){

                                $this->resultado[1]= 0;
                                $this->resultado[2]= " | ".str_replace('"','',str_replace("'","",$var5->faultcode." - ".$var5->faultstring));

                            }

                        }

                    }

                }

            }

        }

        return $this;
    }

}
