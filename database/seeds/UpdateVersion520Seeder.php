<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UpdateVersion520Seeder extends Seeder
{
    public function run()
    {
        $this->call(TipoMinutaEmpenhoSeeder::class);
        $this->call(EtapaSeeder::class);
        $this->call(TipoCompraSeeder::class);
    }
}
