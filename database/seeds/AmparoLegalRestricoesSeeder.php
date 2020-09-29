<?php

use Illuminate\Database\Seeder;

class AmparoLegalRestricoesSeeder extends Seeder
{
    public function run()
    {
        DB::table('codigos')->insert([
            'descricao' => 'Restrições do amparo legal',
            'visivel' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $ultimoCodigo = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            [
                'codigo_id' => $ultimoCodigo,
                'descres' => 'ADMIN',
                'descricao' => 'Administração Emitente'
            ],
            [
                'codigo_id' => $ultimoCodigo,
                'descres' => 'UGEMITENTE',
                'descricao' => 'UG Emitente'
            ],
            [
                'codigo_id' => $ultimoCodigo,
                'descres' => 'UGFAVOR',
                'descricao' => 'UG Favorecida'
            ],
            [
                'codigo_id' => $ultimoCodigo,
                'descres' => 'AGEXEC',
                'descricao' => 'Agência Executiva'
            ],
            [
                'codigo_id' => $ultimoCodigo,
                'descres' => 'NATDESP',
                'descricao' => 'Natureza da Despesa'
            ]
        ]);

        $ultimoCodigoItem = \App\Models\Codigoitem::all()->last()->id;

        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 45, 'tipo_restricao_id' => $ultimoCodigoItem -2, 'codigo_restricao' => '32314', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 46, 'tipo_restricao_id' => $ultimoCodigoItem -2, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 47, 'tipo_restricao_id' => $ultimoCodigoItem -2, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 53, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 53, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 53, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 54, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 54, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 55, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 55, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 55, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 56, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 56, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 57, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 57, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 57, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 58, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 58, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 59, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 59, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 59, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 60, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 60, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 61, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 61, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 61, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 62, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 62, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 63, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 63, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 63, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 64, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 64, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 65, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 65, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 65, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 66, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 66, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 67, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 67, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 67, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 68, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 68, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 69, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 69, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 69, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 70, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 70, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 71, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 71, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 71, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 72, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 72, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 73, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 73, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 73, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 74, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 74, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 75, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 75, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 75, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 76, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 76, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 77, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 77, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 77, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 78, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 78, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 79, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 79, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 79, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 80, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 80, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 81, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 81, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 81, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 82, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 82, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 83, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 83, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 83, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 84, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 84, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 85, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 85, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 85, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 86, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 86, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 87, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 87, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 87, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 88, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 88, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 89, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 90, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 91, 'tipo_restricao_id' => $ultimoCodigoItem -2, 'codigo_restricao' => '24209', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 97, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 97, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 97, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 98, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 98, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 99, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 99, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 99, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 100, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 100, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 101, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 101, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 101, 'tipo_restricao_id' => $ultimoCodigoItem -1, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 102, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 102, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 103, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 105, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '2000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 105, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339036', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 105, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339039', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 105, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339092', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 106, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '2000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 106, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339033', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 107, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '63000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 107, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339039', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 108, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '1000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 108, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339033', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 123, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 123, 'tipo_restricao_id' => $ultimoCodigoItem -4, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 124, 'tipo_restricao_id' => $ultimoCodigoItem -3, 'codigo_restricao' => '2000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 124, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339036', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 124, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339039', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['restricao_id' => 124, 'tipo_restricao_id' => $ultimoCodigoItem -0, 'codigo_restricao' => '339092', 'created_at' => now(), 'updated_at' => now()]);
    }
}
