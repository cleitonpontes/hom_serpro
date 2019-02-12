<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = \App\Models\BackpackUser::create([
            'name' => 'HELES RESENDE SILVA JÚNIOR',
            'cpf' => '700.744.021-53',
            'email' => 'heles.junior@agu.gov.br',
            'ugprimaria' => '8',
            'password' => bcrypt('123456'),
        ]);
        $user->assignRole('Administrador');

        $user = \App\Models\BackpackUser::create([
            'name' => 'JUNIA MARIA MARTINS DIAS',
            'cpf' => '027.291.246-83',
            'email' => 'junia.dias@agu.gov.br',
            'ugprimaria' => '3',
            'password' => bcrypt('123456'),
        ]);
        $user->assignRole('Administrador');

        $user = \App\Models\BackpackUser::create([
            'name' => 'IVALDO DE MESQUITA VERAS',
            'cpf' => '619.798.991-34',
            'email' => 'ivaldo.veras@agu.gov.br',
            'ugprimaria' => '8',
            'password' => bcrypt('123456'),
        ]);
        $user->assignRole('Administrador');

        $user = \App\Models\BackpackUser::create([
            'name' => 'ALAN WALLACE ANTUNES DOS SANTOS',
            'cpf' => '034.492.531-58',
            'email' => 'alan.santos@agu.gov.br',
            'ugprimaria' => '8',
            'password' => bcrypt('123456'),
        ]);
        $user->assignRole('Administrador');

        $user = \App\Models\BackpackUser::create([
            'name' => '	CRISTIANO ROBERTO POLATO BARREIRA',
            'cpf' => '001.752.446-69',
            'email' => 'cristiano.barreira@basis.com.br',
            'ugprimaria' => '8',
            'password' => bcrypt('123456'),
        ]);
        $user->assignRole('Administrador');

        $user = \App\Models\BackpackUser::create([
            'name' => 'FILIPE BRITO CORREIA',
            'cpf' => '007.572.521-50',
            'email' => 'filipe.correia@agu.gov.br',
            'ugprimaria' => '8',
            'password' => bcrypt('123456'),
        ]);
        $user->assignRole('Administrador');

    }
}
