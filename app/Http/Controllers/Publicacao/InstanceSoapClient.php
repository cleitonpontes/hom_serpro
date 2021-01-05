<?php

namespace App\Http\Controllers\Publicacao;

use SoapClient;


class InstanceSoapClient extends BaseSoapController implements InterfaceInstanceSoap
{

    public static function init($headers){

        $wsdlUrl = self::getWsdl();

        $soapClientOptions = [
            'stream_context' => self::generateContext(),
            'cache_wsdl'     => WSDL_CACHE_NONE,
            'trace' => 1
        ];

        $soapClient = new SoapClient($wsdlUrl, $soapClientOptions);

        $soapClient->__setSoapHeaders($headers);

        return $soapClient;
    }

}
