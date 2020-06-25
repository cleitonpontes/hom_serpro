<?php


namespace App\XML\ChainOfResponsabilities\Contratos;


abstract class Handler
{
    private $successor = null;

    public function __construct(Handler $handler = null)
    {
        $this->successor = $handler;
    }

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
