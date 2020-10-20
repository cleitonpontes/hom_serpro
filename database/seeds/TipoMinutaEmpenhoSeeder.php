<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class TipoMinutaEmpenhoSeeder extends Seeder
{
    public function run()
    {


        DB::table('codigos')->insert([
            'descricao' => 'Tipo Minuta Empenho',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '1',
            'descricao' => 'Original',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '2',
            'descricao' => 'Alteração',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '3',
            'descricao' => 'Contrato Continuado',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);


    }
}
