<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UserSeeder extends Seeder
{
    /**
     *
     */
    public function run()
    {

        $user = \Backpack\Base\app\Models\BackpackUser::create([
            'name' => 'Super Administrador',
            'cpf' => '111.111.111-11',
            'email' => 'sadmin@teste.com',
            'ugprimaria' => '110062',
            'password' => bcrypt('123456'),
        ]);

        $user->assignRole('Super Administrador');
    }
}
