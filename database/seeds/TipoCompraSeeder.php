<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

class TipoCompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('codigos')->insert([
            'descricao' => 'Tipo Compra',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '01',
            'descricao' => 'SISPP',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '02',
            'descricao' => 'SISRP',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
