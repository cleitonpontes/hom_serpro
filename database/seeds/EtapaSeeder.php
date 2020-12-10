<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class EtapaSeeder extends Seeder
{
    public function run()
    {

        DB::table('codigos')->insert([
            'descricao' => 'Etapa',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '1',
            'descricao' => 'Etapa 1',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '2',
            'descricao' => 'Etapa 2',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '3',
            'descricao' => 'Etapa 3',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '4',
            'descricao' => 'Etapa 4',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '5',
            'descricao' => 'Etapa 5',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '6',
            'descricao' => 'Etapa 6',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '7',
            'descricao' => 'Etapa 7',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
