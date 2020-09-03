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
//            dd($this->service->ConsultaTodosMotivosIsencao());

            $countryCode = 'DK';
            $vatNumber = '47458714';

            $params = [
                'countryCode' => request()->input('countryCode') ? request()->input('countryCode') : $countryCode,
                'vatNumber'   => request()->input('vatNumber') ? request()->input('vatNumber') : $vatNumber
            ];
            $response = $this->service->checkVat($params);
            return view ('bienes-servicios-soap', compact('response'));
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function clima(){
        try {
            self::setWsdl('http://www.webservicex.net/globalweather.asmx?WSDL');
            $this->service = InstanceSoapClient::init();

            $cities = $this->service->GetCitiesByCountry(['CountryName' => 'Peru']);
            $ciudades = $this->loadXmlStringAsArray($cities->GetCitiesByCountryResult);
            dd($ciudades['Table'][1]);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
