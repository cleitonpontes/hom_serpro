<?php


namespace App\XML\ChainOfResponsabilities\Contratos;
use App\Models\Contratosfpadrao;
use DOMDocument;
use Illuminate\Database\Eloquent\Model;
use App\Models\SfDadosBasicos;

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
     * @return object
     * @author Franklin J. G. Silva
     */
    final public function manipulador(string $tagName,string $xml,array $dados,Model $modObjeto): ?object
    {
        $processed = $this->processing($tagName,$xml,$dados,$modObjeto);
        dd($modObjeto);
        if ($processed === null && $this->successor !== null) {
            $processed = $this->successor->manipulador($tagName,$xml,$dados,$modObjeto);
        }

        return $processed;
    }

    final public function processing(string $tagName,string $xml, array $params,Model $model): ?object
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($xml);
        $nomeModel = $this->retornaNomeModel($doc,$tagName);
        $model = new $nomeModel;

        $noAtual = $doc->getElementsByTagName($tagName)->item(0)->childNodes;

        foreach($noAtual as $value => $item){
            $no = $doc->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
           ($no <= 1) ? $params[strtolower($item->nodeName)]=$item->nodeValue : '';
        }

        $model = $model->newInstance($params);
        $model->save($params);
//        foreach($documentoHabil as $value => $item){
//            $no = $doc->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
//            ($no > 1) ? $this->processing($item->nodeName,$xml,$params,$model) : '';
//        }
//        dd($model);
//        return $modDadosBasicos;
        return $model;

    }

    final public function processaXml(string $tagName,string $xml, array $params,Model $model): ?object
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML( $xml );
        $documentoHabil = $doc->getElementsByTagName($tagName)->item(0)->childNodes;

        foreach($documentoHabil as $value => $item){
            $no = $doc->getElementsByTagName($item->nodeName)->item(0)->childNodes->length;
            ($no > 1) ? $this->manipulador($item->nodeName,$xml,$params,$model) : '';
        }
//        dd($params);
        dd('parei');
//        $modDadosBasicos = $model->newInstance($params);
//        $modDadosBasicos->save($params);
        return $modDadosBasicos;

    }

    public function retornaNomeModel(DOMDocument $doc,string $tagName): ?string
    {
//        dd($doc);
//        dd($doc->getElementsByTagName($tagName)->item(0));
        $nomeNo = $doc->getElementsByTagName($tagName)->item(0)->nodeName;

        if ($nomeNo == 'deducao' || $nomeNo == 'encargos' || $nomeNo == 'dadosPgto') {
            $nomeModel = 'App\Models\Sf' . ucfirst($doc->getElementsByTagName($tagName)->item(0)->nodeName);
        }elseif(true){
            $nomeModel = 'App\Models\Sf' . ucfirst($doc->getElementsByTagName($tagName)->item(0)->nodeName);
        }else{
            $nomeModel = 'App\Models\Sf' . ucfirst($doc->getElementsByTagName($tagName)->item(0)->nodeName);
        }

        return $nomeModel;
    }

    abstract protected function process(string $xml,Contratosfpadrao $contratosfpadrao): ?object;

}
