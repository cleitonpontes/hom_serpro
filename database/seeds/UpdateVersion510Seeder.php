<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UpdateVersion510Seeder extends Seeder
{
    public function run()
    {
        $this->call(UpdateCodigoSiasgUnidadeSeeder::class);
        $this->call(UpdateNumeroContratoeHistoricoSeeder::class);
        $this->call(UpdateNumLiciContratoeHistoricoSeeder::class);
        $this->call(UpdateCatmatCatserSeeder::class);

        $this->call(EstadosSeeder::class);
        $this->call(MunicipiosSeeder::class);

        $this->call(InsertSuperiorOrgaoUnidadeSeeder::class);
        $this->call(UpdateCamposSiasgUnidade::class);
    }
}
