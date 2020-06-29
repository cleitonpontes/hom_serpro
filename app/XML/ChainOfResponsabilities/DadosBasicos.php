<?php


namespace App\XML\ChainOfResponsabilities;


use App\XML\ChainOfResponsabilities\Contratos\Handler;
use DOMDocument;

class DadosBasicos extends Handler
{

    public function __construct(Handler $handler = null)
    {
        parent::__construct($handler);
    }

    protected function processing(string $xml, array $dados): ?array
    {


        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML( $xml );
        $documentoHabil = $doc->getElementsByTagName('dadosBasicos')->item(0)->childNodes;

        foreach($documentoHabil as $value => $item){
            $dados[strtolower($item->nodeName)]=$item->nodeValue;
        }





        $documentoHabil = new DOMDocument('1.0', 'utf-8');
        $documentoHabil = $this->retonaNoDocumentoHabil($xml);
        dd($documentoHabil);
        $valor = $this->retonarValorDoNo("codUgEmit",$xml);

        dd($valor);

        return null;
    }
}
