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
//        $teste = $this->RecurseXML($xmlSiafi);
//        dd($teste);
        $document = new DOMDocument('1.0', 'utf-8');

        $document->loadXML($xmlSiafi);
        $xpath = new \DOMXPath($document);
       $no = $xpath->query("//documentoHabil/*");
       dd($no);
//        $document = new DOMDocument('1.0', 'utf-8');
//        $document->loadXML($xmlSiafi);
//        $xml = simplexml_import_dom($document);
//        dd($xmlSiafi);
        $xml = file_get_contents('../app/XML/SIAFI.xml');
        $xml = simplexml_load_string(str_replace(':', '', $xml));
        $xml = new \SimpleXMLIterator($xml, 0, false);

        dd($xml->rewind());
        dd($xml->asXML());

        for ($xml->rewind();$xml->valid();$xml->next()) {

            dump($xml->getName());

            if ($xml->haschildren()) {
                $xml->current()->children()->attributes();
            }
        }
        dd('teste iterator');

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

    public function retornaXml()
    {
        return <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<universe xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" -
xsi:noNamespaceSchemaLocation="http://s127-fr.ogame.gameforge.com/api/xsd/universe.xsd" -
timestamp="1405413350" serverId="fr127">
    <planet id="1" player="1" name="Arakis" coords="1:1:2">
        <moon id="2" name="Mond" size="4998"/>
    </planet>
    <planet id="33620176" player="100000" name="GameAdmin" coords="1:1:3"/>
    <planet id="33620179" player="100003" name="Heimatplanet" coords="1:1:1"/>
    <planet id="33620186" player="100004" name="OGame Team" coords="6:250:1"/>
    <planet id="33620242" player="100058" name="KnS" coords="9:1:6">
        <moon id="33668391" name="Lune" size="8831"/>
    </planet>
</universe>
EOT;
    }

    function RecurseXML($xml,$parent="")
    {
        $child_count = 0;
        foreach($xml as $key=>$value)
        {
            $child_count++;
            if($this->RecurseXML($value,$parent.".".$key) == 0)  // no childern, aka "leaf node"
            {
                print($parent . "." . (string)$key . " = " . (string)$value . "<BR>\n");
            }
        }
        return $child_count;
    }

}
