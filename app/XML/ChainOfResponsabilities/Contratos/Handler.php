<?php


namespace App\XML\ChainOfResponsabilities\Contratos;
use DOMDocument;

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
    final public function manipulador(string $xml,array $dados): ?array
    {
        $processed = $this->processing($xml,$dados);

        if ($processed === null && $this->successor !== null) {
            // the request has not been processed by this handler => see the next
            $processed = $this->successor->manipulador($xml,$dados);
        }

        return $processed;
    }


    abstract protected function processing(string $xml,array $dados): ?array;
}
