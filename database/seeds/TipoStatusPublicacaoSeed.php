<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class TipoStatusPublicacaoSeed extends Seeder
{
    public function run()
    {

        DB::table('codigos')->insert([
            'descricao' => 'Situacao Publicacao',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '01',
            'descricao' => 'TRANSFERIDO PARA IMPRENSA',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '02',
            'descricao' => 'PUBLICADO',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '03',
            'descricao' => 'INFORMADO',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '05',
            'descricao' => 'A PUBLICAR',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '07',
            'descricao' => 'DEVOLVIDO PELA IMPRENSA',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);


    }
}
