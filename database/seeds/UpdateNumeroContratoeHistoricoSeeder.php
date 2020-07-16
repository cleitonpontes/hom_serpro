<?php

use App\Jobs\AlteraMascaraJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UpdateNumeroContratoeHistoricoSeeder extends Seeder
{
    public function run()
    {
        $arrumaCampo = new AlteraMascaraJob("contratos","numero",10,"0",['contratohistorico']);
        $arrumaCampo->handle();
    }
}
