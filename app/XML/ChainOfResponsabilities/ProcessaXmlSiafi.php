<?php


namespace App\XML\ChainOfResponsabilities;

use App\Models\Contratosfpadrao;
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

        $this->processaXml('documentoHabil',$xmlSiafi,$arrayXml,$contratosfpadrao);

    }

}
