<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class TipoMudancaSeed extends Seeder
{
    public function run()
    {

        DB::table('codigos')->insert([
            'descricao' => 'Tipo Publicacao',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '01',
            'descricao' => 'INCLUSAO',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '02',
            'descricao' => 'ALTERACAO / RETIFICACAO',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '03',
            'descricao' => 'EXCLUSAO / ANULACAO',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
