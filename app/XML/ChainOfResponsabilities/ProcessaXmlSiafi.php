<?php


namespace App\XML\ChainOfResponsabilities;

use App\Models\Contratosfpadrao;
use App\XML\ChainOfResponsabilities\Contratos\Handler;
use Illuminate\Database\Eloquent\Model;
use DOMDocument;

class ProcessaXmlSiafi extends Handler
{


    public function __construct(Handler $handler = null)
    {
        parent::__construct($handler);
    }

    public function process(string $xmlSiafi,Contratosfpadrao $contratosfpadrao): ?object
    {

        $xml = new \SimpleXMLIterator($xmlSiafi);
        $xml->
        $iterator = new \RecursiveIteratorIterator($xml);

        $document = new DOMDocument('1.0', 'utf-8');
        $document->loadXML($xmlSiafi);

        $documentoHabil = $document->getElementsByTagName('documentoHabil')->item(0)->childNodes;

        $arrayObjetos = [];

        $arrayFilhos = $this->pegaNosFilhos('documentoHabil',$document);
//        dd($arrayFilhos);
        if(!empty($arrayFilhos)){
            foreach($arrayFilhos as $key => $value){
                $arrayObjetos[$key] = $this->persisteModelo($params,$key,$value,$xmlSiafi,$contratosfpadrao,$document);
            }
        }

        dd($arrayObjetos);

    }

    public function persisteModelo(array $params,int $key,string $tagName,string $xml,Model $model,DOMDocument $document): ?object
    {

        $nomeModel = $this->retornaNomeModel($document,$tagName);

        $model = new $nomeModel;

        $noAtual = $document->getElementsByTagName($tagName)->item(0)->childNodes;
//        dd($noAtual);
        dump($noAtual->item(0));
        dump($noAtual->item(1));
        dump($noAtual->item(2));
        dump($noAtual->item(3));
        dump($noAtual->item(4));
        dump($noAtual->item(5));
        dump($noAtual->item(6));

        $deducao = [];
        foreach($noAtual as $value => $item){
            $no = $document->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }

        $model = $model->newInstance($params);
        $model->save($params);
        return $model;

    }

    public function pegaNosFilhos(string $tagName,DOMDocument $document): ?array
    {
        $documentoHabil = $document->getElementsByTagName($tagName)->item(0)->childNodes;
        $arrayFilhos =[];
        foreach($documentoHabil as $value => $item){
            $no = $document->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
            ($no > 1) ? $arrayFilhos[$value] = $item->nodeName : '';
        }

        return $arrayFilhos;
    }

    public function persisteDadosBasicos()
    {

    }

}
