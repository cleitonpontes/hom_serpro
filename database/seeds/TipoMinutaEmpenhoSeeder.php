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
            'descricao' => 'Ordinário',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '3',
            'descricao' => 'Estimativo',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '5',
            'descricao' => 'Global',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);


    }
}
