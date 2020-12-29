<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

class TipoNormaPublicacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         DB::table('codigos')->insert([
             'descricao' => 'Tipo Norma Publicação',
             'visivel' => false,
             'created_at' => now(),
             'updated_at' => now()
         ]);

         $itemCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '89',
            'descricao' => 'Extrato de Acordo de Cooperação Técnica',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '90',
            'descricao' => 'Extrato de Adesão',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '534',
            'descricao' => 'Extrato de Apostilamento',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '434',
            'descricao' => 'Extrato de Cessão',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '98',
            'descricao' => 'Extrato de Comodato',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '100',
            'descricao' => 'Extrato de Compromisso',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '102',
            'descricao' => 'Extrato de Concessão de Uso',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '103',
            'descricao' => 'Extrato de Contrato',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '104',
            'descricao' => 'Extrato de Convênio',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '105',
            'descricao' => 'Extrato de Credenciamento',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '194',
            'descricao' => 'Extrato de Rescisão',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '130',
            'descricao' => 'Extrato de Sub-rogação',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '131',
            'descricao' => 'Extrato de Termo Aditivo',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '1274',
            'descricao' => 'Extrato de Termo de Execução Descentralizada',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('codigoitens')->insert([
            'codigo_id' => $itemCodigo,
            'descres' => '271',
            'descricao' => 'Retificação',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
