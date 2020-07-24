<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UpdateVersion510SeederTableSeeder extends Seeder
{
    public function run()
    {
        $this->call(UpdateCodigoSiasgUnidadeSeeder::class);
        $this->call(UpdateNumeroContratoeHistoricoSeeder::class);
        $this->call(UpdateNumLiciContratoeHistoricoSeeder::class);
        $this->call(UpdateCatmatCatserSeederTableSeeder::class);
        $this->call(UpdateCamposSiasgUnidadeTableSeeder::class);

    }
}
