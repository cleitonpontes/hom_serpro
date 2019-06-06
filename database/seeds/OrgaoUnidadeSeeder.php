<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class OrgaoUnidadeSeeder extends Seeder
{
    public function run()
    {
        DB::table('orgaossuperiores')->insert([
            'codigo' => '63000',
            'nome' => 'ADVOCACIA-GERAL DA UNIÃO',
            'situacao' => true
        ]);

        DB::table('orgaos')->insert([
            'orgaosuperior_id' => '1',
            'codigo' => '63000',
            'codigosiasg' => '20114',
            'nome' => 'ADVOCACIA-GERAL DA UNIÃO',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110161',
            'codigosiasg' => '110161',
            'nome' => 'SUPERIN. DE ADM. NO DISTRITO FEDERAL',
            'nomeresumido' => 'SAD/DF',
            'telefone' => '(61) 2026-7000',
            'tipo' => 'E',
            'situacao' => true
        ]);


    }
}
