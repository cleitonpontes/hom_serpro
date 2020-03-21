<?php

namespace Tests\Unit\Models;

use App\Models\Comunica;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComunicaTest extends TestCase
{

    public function testGetOrgao()
    {
        $comunica = new Comunica();

        $comunica->orgao_id = 0;
        $this->assertEquals('Todas', $comunica->getOrgao());

        $comunica->orgao_id = 1;
        $this->assertEquals('63000 - ADVOCACIA-GERAL DA UNIÃO', $comunica->getOrgao());
    }

    public function testGetSituacao()
    {
        $comunica = new Comunica();

        $comunica->situacao = $comunica::COMUNICA_SITUACAO_INACABADO;
        $this->assertEquals('Inacabado', $comunica->getSituacao());

        $comunica->situacao = Comunica::COMUNICA_SITUACAO_PRONTO_PARA_ENVIO;
        $this->assertEquals('Pronto para Envio', $comunica->getSituacao());

        $comunica->situacao = Comunica::COMUNICA_SITUACAO_ENVIADO;
        $this->assertEquals('Enviado', $comunica->getSituacao());
    }

}
