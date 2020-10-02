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

        $restricoesAmparoLegal = \App\Models\Codigo::all()->last()->id;

        DB::table('codigoitens')->insert([
            [
                'codigo_id' => $restricoesAmparoLegal,
                'descres' => 'ADMIN',
                'descricao' => 'Administração Emitente',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'codigo_id' => $restricoesAmparoLegal,
                'descres' => 'UGEMITENTE',
                'descricao' => 'UG Emitente',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'codigo_id' => $restricoesAmparoLegal,
                'descres' => 'UGFAVOR',
                'descricao' => 'UG Favorecida',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'codigo_id' => $restricoesAmparoLegal,
                'descres' => 'AGEXEC',
                'descricao' => 'Agência Executiva',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'codigo_id' => $restricoesAmparoLegal,
                'descres' => 'NATDESP',
                'descricao' => 'Natureza da Despesa',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $ultimoCodigoItem = \App\Models\Codigoitem::all()->last()->id;

        $itemAdministracaoEmitente = $ultimoCodigoItem - 4;
        $itemUgEmitente = $ultimoCodigoItem - 3;
        $itemUgFavorecida = $ultimoCodigoItem - 2;
        $itemAgenciaExecutiva = $ultimoCodigoItem - 1;
        $itemNaturezaDespesa = $ultimoCodigoItem - 0;

        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 45, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '32314', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 45, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '32314', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 45, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '32314', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 45, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '32314', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 45, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '32314', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 46, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 47, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 53, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 53, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 53, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 54, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 54, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 55, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 55, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 55, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 56, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 56, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 57, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 57, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 57, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 58, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 58, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 59, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 59, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 59, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 60, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 60, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 61, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 61, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 61, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 62, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 62, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 63, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 63, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 63, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 64, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 64, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 65, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 65, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 65, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 66, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 66, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 67, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 67, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 67, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 68, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 68, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 69, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 69, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 69, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 70, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 70, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 71, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 71, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 71, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 72, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 72, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 73, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 73, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 73, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 74, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 74, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 75, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 75, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 75, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 76, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 76, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 77, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 77, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 77, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 78, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 78, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 79, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 79, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 79, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 80, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 80, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 81, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 81, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 81, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 82, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 82, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 83, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 83, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 83, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 84, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 84, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 85, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 85, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 85, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 86, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 86, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 87, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 87, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 87, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 88, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 88, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 89, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 90, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 91, 'tipo_restricao_id' => $itemUgFavorecida, 'codigo_restricao' => '24209', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 97, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 97, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 97, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 98, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 98, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 99, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 99, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 99, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 100, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 100, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 101, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 101, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '4', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 101, 'tipo_restricao_id' => $itemAgenciaExecutiva, 'codigo_restricao' => 'S', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 102, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 102, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 103, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '20415', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 105, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '2000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 105, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339036', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 105, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339039', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 105, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339092', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 106, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '2000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 106, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339033', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 107, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '63000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 107, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339039', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 108, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '1000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 108, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339033', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 123, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '5', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 123, 'tipo_restricao_id' => $itemAdministracaoEmitente, 'codigo_restricao' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 124, 'tipo_restricao_id' => $itemUgEmitente, 'codigo_restricao' => '2000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 124, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339036', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 124, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339039', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal_restricoes')->insert(['amparo_legal_id' => 124, 'tipo_restricao_id' => $itemNaturezaDespesa, 'codigo_restricao' => '339092', 'created_at' => now(), 'updated_at' => now()]);
    }
}
