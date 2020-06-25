<?php


namespace App\XML\ChainOfResponsabilities;


use App\XML\ChainOfResponsabilities\Contratos\Handler;
use DOMDocument;

class DadosBasicos extends Handler
{

    protected function processing(string $xml, array $dados): ?array
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML( $xml );
        $XMLresults     = $doc->getElementsByTagName("codUgEmit");
        $output = $XMLresults->item(0)->nodeValue;

        dd($output);

        return null;
    }
}
