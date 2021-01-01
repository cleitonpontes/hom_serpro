<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

class MotivoIsencaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('codigos')->insert([
            'descricao' => 'Motivo Isenção',
            'visivel' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '21',
            'descricao' => 'Atos oficiais administrativos, normativos e de pessoal dos ministérios e órgãos subordinados',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '7',
            'descricao' => 'Atos oficiais administrativos, normativos e de pessoal emanados da Câmara dos Deputados',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '4',
            'descricao' => 'Atos oficiais administrativos, normativos e de pessoal emanados da PR e dos órgãos que a integram',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '5',
            'descricao' => 'Atos oficiais administrativos, normativos e de pessoal emanados do Congresso Nacional',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '6',
            'descricao' => 'Atos oficiais administrativos, normativos e de pessoal emanados do Senado Federal',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '8',
            'descricao' => 'Atos oficiais administrativos, normativos e de pessoal emanados do TCU e MPU',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '9',
            'descricao' => 'Atos oficiais administrativos, normativos e de pessoal emanados dos órgãos do Poder Judiciário',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '10',
            'descricao' => 'Despachos e Atas das sessões dos tribunais',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '3',
            'descricao' => 'Editais de interesse dos beneficiários da assistência judiciária gratuita. Art. 32 da Portaria nº 268/2009-IN-DG',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '11',
            'descricao' => 'Notas de expediente dos cartórios',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '41',
            'descricao' => 'Uso exclusivo da Imprensa Nacional',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
