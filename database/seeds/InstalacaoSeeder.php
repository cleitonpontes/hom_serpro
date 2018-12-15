<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class InstalacaoSeeder extends Seeder
{
    public function run()
    {
        DB::table('instalacoes')->insert(['nome' => 'DF - Brasília - Sede I']);
        DB::table('instalacoes')->insert(['nome' => 'DF - Brasília - Sede II']);
    }
}
