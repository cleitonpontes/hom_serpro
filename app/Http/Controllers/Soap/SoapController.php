<?php


namespace App\Http\Controllers\Soap;


class SoapController extends BaseSoapController
{
    private $service;

    public function consulta(){
        try {

            self::setWsdl('https://homologwsincom.in.gov.br/services/servicoIN?wsdl');

            $this->service = InstanceSoapClient::init();

            dd($this->service->__getTypes());

            $response = $this->service->checkVat($params);
            return view ('bienes-servicios-soap', compact('response'));
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

}
