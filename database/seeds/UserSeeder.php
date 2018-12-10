<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = \App\User::create([
            'name' => 'Super Administrador',
            'cpf' => '111.111.111-11',
            'email' => 'helesjunior@gmail.com',
            'ugprimaria' => '1',
            'password' => bcrypt('123456'),
        ]);
    }
}
