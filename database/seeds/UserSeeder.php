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
            'email' => 'helesjunior@gmail.com',
            'ugprimaria' => '1',
            'password' => bcrypt('123456'),
        ]);

        $user->assignRole('Administrador');
    }
}
