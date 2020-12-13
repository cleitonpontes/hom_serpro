<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class TipoQualificacaoSeeder extends Seeder
{
    public function run()
    {


        DB::table('codigos')->insert([
            'descricao' => 'Tipo Qualificacao Contrato',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '1',
            'descricao' => 'ACRÉSCIMO / SUPRESSÃO',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '2',
            'descricao' => 'VIGÊNCIA',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '3',
            'descricao' => 'FORNECEDOR',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '4',
            'descricao' => 'REAJUSTE',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '5',
            'descricao' => 'INFORMATIVO',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);


    }
}
