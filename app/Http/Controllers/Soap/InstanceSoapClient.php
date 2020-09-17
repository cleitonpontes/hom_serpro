<?php


namespace App\Http\Controllers\Soap;

use SoapClient;

class InstanceSoapClient extends BaseSoapController implements InterfaceInstanceSoap
{
    public static function init(){
        $wsdlUrl = self::getWsdl();

        $soapClientOptions = [
            'stream_context' => self::generateContext(),
            'cache_wsdl'     => WSDL_CACHE_NONE
        ];
        $soapClient = new SoapClient($wsdlUrl, $soapClientOptions);
        $headers = self::montaCabecalho();
        return $soapClient->__setSoapHeaders($headers);
    }

    public static function montaCabecalho()
    {
        $soapServer = self::getWsdl();

        $arrContextOptions = array("ssl" => array("verify_peer" => false , "verify_peer_name" => false , 'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT));

        $options = array(
            'soap_version'=>SOAP_1_2,
            'exceptions'=>true,
            'trace'=>1,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create($arrContextOptions)
        );

        try {
            $securityNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

            $auth = new stdClass();
            $auth->Username = new SoapVar('user', XSD_STRING, null, null, 'Username', $securityNS);
            $auth->Password = new SoapVar('pass', XSD_STRING, null, null, 'Password', $securityNS);
            $usernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, null, null, 'UsernameToken', $securityNS);
            $security = new SoapVar($usernameToken, SOAP_ENC_OBJECT, null, null, 'Security', $securityNS);

            $headers[] = new SoapHeader($securityNS , 'Security' , $security , true);

            return $headers;

        } catch ( SoapFault $fault ) {
            echo '<pre>' ;
            var_dump($fault);
            echo '</pre>' ;
        }
    }
}
