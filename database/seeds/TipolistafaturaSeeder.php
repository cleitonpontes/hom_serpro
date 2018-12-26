<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class TipolistafaturaSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipolistafatura')->insert([ 'nome' => 'FORNECIMENTO DE BENS', 'situacao' => 1]);
        DB::table('tipolistafatura')->insert([ 'nome' => 'LOCAÇÕES', 'situacao' => 1]);
        DB::table('tipolistafatura')->insert([ 'nome' => 'PRESTAÇÃO DE SERVIÇOS', 'situacao' => 1]);
        DB::table('tipolistafatura')->insert([ 'nome' => 'REALIZAÇÃO DE OBRAS', 'situacao' => 1]);
        DB::table('tipolistafatura')->insert([ 'nome' => 'PEQUENOS CREDORES (Inciso II, 24, 8.666 e paragrafo 1º)', 'situacao' => 1]);
        DB::table('tipolistafatura')->insert([ 'nome' => 'VINCULAÇÕES ESPECÍFICAS', 'situacao' => 1]);
    }
}
