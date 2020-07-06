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
        $document = new DOMDocument('1.0', 'utf-8');
        $document->loadXML($xmlSiafi);
        $documentoHabil = $document->getElementsByTagName('documentoHabil')->item(0)->childNodes;

        dd($documentoHabil);
        foreach($documentoHabil as $value => $item){
            $no = $doc->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
            ($no > 1) ? $this->manipulador($item->nodeName,$xml,$params,$model) : '';
        }
        $modelo = $this->persisteModelo('dadosBasicos',$xmlSiafi,$contratosfpadrao,$document);
        dd($modelo);
    }

    public function persisteModelo(string $tagName,string $xml,Model $model,DOMDocument $document): ?object
    {

        $nomeModel = $this->retornaNomeModel($document,$tagName);
        $model = new $nomeModel;

        $noAtual = $document->getElementsByTagName($tagName)->item(0)->childNodes;

        foreach($noAtual as $value => $item){
            $no = $document->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }

        $model = $model->newInstance($params);
        $model->save($params);
        return $model;

    }

}
