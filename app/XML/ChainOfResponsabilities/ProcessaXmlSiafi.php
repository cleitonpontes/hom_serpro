<?php


namespace App\XML\ChainOfResponsabilities;

use App\Models\Contratosfpadrao;
use App\Models\SfDadosBasicos;
use App\Models\SfDocOrigem;
use App\Models\SfPco;
use App\Models\SfPcoItem;
use App\XML\ChainOfResponsabilities\Contratos\Handler;


class ProcessaXmlSiafi extends Handler
{


    public function __construct(Handler $handler = null)
    {
        parent::__construct($handler);
    }

    public function process(string $xmlSiafi,Contratosfpadrao $contratosfpadrao): ?object
    {
        //processa nó dadosBasicos
        $arrayXml = ['sfpadrao_id' => $contratosfpadrao->id];
        $modSfDadosBasicos = $this->processaTabelaSfDadosBasicos($xmlSiafi,$arrayXml);
        //processa nó pco
        $modSfPco = $this->processaTabelaPco($xmlSiafi,$arrayXml);

        //processa nó docOrigem
        $arrayXml = ['sfdadosbasicos_id' => $modSfDadosBasicos->id];
        $modSfDocOrigem = $this->processaTabelaSfDocOrigem($xmlSiafi,$arrayXml);


//        processa nó pcoItem
        $arrayXml = ['sfpco_id' => $modSfPco->id];
        $modSfPcoItem = $this->processaTabelaPcoItem($xmlSiafi,$arrayXml);
        dd($modSfPcoItem);
    }

    public function processaTabelaSfDadosBasicos(string $xmlSiafi,array $arrayXml): ?object
    {
        $tagName = 'dadosBasicos';
        $modSfDadosBasicos = new SfDadosBasicos();
        $modSfDadosBasicos = $this->manipulador($tagName,$xmlSiafi,$arrayXml,$modSfDadosBasicos);
        return $modSfDadosBasicos;
    }

    public function processaTabelaSfDocOrigem(string $xmlSiafi,array $arrayXml): ?object
    {
        $tagName = 'docOrigem';
        $modSfDocOrigem = new SfDocOrigem();
        $modSfDocOrigem = $this->manipulador($tagName,$xmlSiafi,$arrayXml,$modSfDocOrigem);
        return $modSfDocOrigem;
    }

    public function processaTabelaPco(string $xmlSiafi,array $arrayXml): ?object
    {
        $tagName = 'pco';
        $modSfPco = new SfPco();
        $modSfPco = $this->manipulador($tagName,$xmlSiafi,$arrayXml,$modSfPco);
        return $modSfPco;
    }

    public function processaTabelaPcoItem(string $xmlSiafi,array $arrayXml): ?object
    {
        $tagName = 'pcoItem';
        $modSfPcoItem = new SfPcoItem();
        $modSfPcoItem = $this->manipulador($tagName,$xmlSiafi,$arrayXml,$modSfPcoItem);
        return $modSfPcoItem;
    }
}
