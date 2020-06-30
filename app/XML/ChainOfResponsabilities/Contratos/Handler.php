<?php


namespace App\XML\ChainOfResponsabilities\Contratos;
use App\Models\Contratosfpadrao;
use DOMDocument;
use Illuminate\Database\Eloquent\Model;

abstract class Handler
{
    private $successor = null;

    public function __construct(Handler $handler = null)
    {
        $this->successor = $handler;
    }

    /**
     * Função recursiva para atribuir a responsabilidade de processar os dados em cadeia
     * @param  string $xml
     * @param array $dados
     * @return array
     * @author Franklin J. G. Silva
     */
    final public function manipulador(string $tagName,string $xml,array $dados,Model $modObjeto): ?object
    {
        $processed = $this->processing($tagName,$xml,$dados,$modObjeto);

        if ($processed === null && $this->successor !== null) {
            $processed = $this->successor->manipulador($tagName,$xml,$dados,$modObjeto);
        }

        return $processed;
    }

    final public function processing(string $tagName,string $xml, array $params,Model $model): ?object
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML( $xml );
        $documentoHabil = $doc->getElementsByTagName($tagName)->item(0)->childNodes;

        foreach($documentoHabil as $value => $item){
            $no = $doc->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
            ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }
        dump($params);
        $modDadosBasicos = $model->newInstance($params);
        $modDadosBasicos->save($params);
        return $modDadosBasicos;

    }

    abstract protected function process(string $xml,Contratosfpadrao $contratosfpadrao): ?object;
}
