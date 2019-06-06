<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = \App\Models\BackpackUser::create([
            'name' => 'USUÁRIO ADMINISTRADOR',
            'cpf' => '111.111.111-11',
            'email' => 'heles.junior@agu.gov.br',
            'ugprimaria' => '1',
            'password' => bcrypt('123456'),
        ]);
        $user->assignRole('Administrador');

    }
}
