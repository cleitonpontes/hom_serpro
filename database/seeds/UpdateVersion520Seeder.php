<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UpdateVersion520Seeder extends Seeder
{
    public function run()
    {
        //seeds anteriores a minuta do empenho
        $this->call(PeriodicidadeSeeder::class);
        $this->call(EscopoGlosaSeeder::class);
        $this->call(ApropriacaoFaturaSeeder::class);
        $this->call(ApropriacaofaseNewSeeder::class);

        //seeds minuta empenho
        $this->call(TipoMinutaEmpenhoSeeder::class);
        $this->call(EtapaSeeder::class);
        $this->call(TipoCompraSeeder::class);
    }
}
