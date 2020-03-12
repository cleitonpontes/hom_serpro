<?php

namespace Tests\Unit\Repositories;

use App\Repositories\Comunica as Repo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComunicaTest extends TestCase
{
    public function testGetSituacoes()
    {
        $com = new Repo();
        $this->assertTrue(is_array($com->getSituacoes()));
    }

}
