<?php


namespace App\XML\ChainOfResponsabilities;


use App\Models\SfDadosBasicos;
use App\XML\ChainOfResponsabilities\Contratos\Handler;
use DOMDocument;
use Illuminate\Support\Facades\DB;

class DadosBasicos extends Handler
{

    public function __construct(Handler $handler = null)
    {
        parent::__construct($handler);
    }

    protected function processing(string $xml, array $params): ?array
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML( $xml );
        $documentoHabil = $doc->getElementsByTagName('dadosBasicos')->item(0)->childNodes;

        foreach($documentoHabil as $value => $item){
            $no = $doc->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }

        $modDadosBasicos = new SfDadosBasicos($params);
        $modDadosBasicos->save();
        return $modDadosBasicos;

    }
}
