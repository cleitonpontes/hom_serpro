<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy

class AmparoLegalSeeder extends Seeder
{
    public function run()
    {
        DB::table('codigoitens')->insert([
            [
                'codigo_id' => 13, // Modalidade Licitação
                'descres' => 'CONSULTA',
                'descricao' => 'Consulta'
            ],
            [
                'codigo_id' => 13,
                'descres' => 'REGDIFER',
                'descricao' => 'Regime Diferenciado'
            ],
            [
                'codigo_id' => 13,
                'descres' => 'SUPRIMENTO',
                'descricao' => 'Suprimento de Fundos'
            ],
            [
                'codigo_id' => 13,
                'descres' => 'NAOAPLICA',
                'descricao' => 'Não se aplica'

            ]
        ]);

        $ultimoCodigoItem = \App\Models\Codigoitem::all()->last()->id;

        DB::table('amparo_legal')->insert(['id' => 1, 'modalidade_id' => 72, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '22', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 2, 'modalidade_id' => 73, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 3, 'modalidade_id' => 73, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 4, 'modalidade_id' => 77, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 5, 'modalidade_id' => 77, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 6, 'modalidade_id' => 71, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 7, 'modalidade_id' => 71, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 8, 'modalidade_id' => 71, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '42', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 9, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '17', 'paragrafo' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 10, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 11, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 12, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 13, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 14, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 15, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 16, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 17, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 18, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 19, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 20, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 21, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 22, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 23, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 24, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 25, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 26, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 27, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 28, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XIX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 29, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 30, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 31, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 32, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 33, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 34, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 35, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 36, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 37, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 38, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXIX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 39, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 40, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 41, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 42, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 43, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 44, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 45, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 10.847 / 2004', 'artigo' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 46, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.652 / 2008', 'artigo' => '8', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 47, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.652 / 2008', 'artigo' => '8', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 48, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.947 / 2009', 'artigo' => '14', 'paragrafo' => '1', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 49, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.512 / 2011', 'artigo' => '17', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 50, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.663 / 2012', 'artigo' => '55', 'paragrafo' => '2', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 51, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.865 / 2013', 'artigo' => '18', 'paragrafo' => '1', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 52, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.873 / 2013', 'artigo' => '42', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 53, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 54, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 55, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 56, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 57, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 58, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 59, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 60, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 61, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 62, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 63, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 64, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 65, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 66, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 67, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 68, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 69, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 70, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 71, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 72, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 73, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 74, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 75, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 76, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 77, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 78, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 79, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 80, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 81, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 82, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 83, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 84, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 85, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 86, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 87, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 88, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 89, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '08', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 90, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '27', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 91, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.759 / 2008', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 92, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.979 / 2020', 'artigo' => '04', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 93, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 94, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 95, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 96, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 97, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 98, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 99, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 100, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 101, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 102, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 103, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 11.652 / 2008', 'artigo' => '27', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 104, 'modalidade_id' => $ultimoCodigoItem - 0, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 105, 'modalidade_id' => $ultimoCodigoItem - 0, 'ato_normativo' => 'ATO 09 / 1995', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 106, 'modalidade_id' => $ultimoCodigoItem - 0, 'ato_normativo' => 'ATO 15 / 1997', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 107, 'modalidade_id' => $ultimoCodigoItem - 0, 'ato_normativo' => 'PR 253.801 / 2001', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 108, 'modalidade_id' => $ultimoCodigoItem - 0, 'ato_normativo' => 'TCU 61.099 / 1967', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 109, 'modalidade_id' => $ultimoCodigoItem - 1, 'ato_normativo' => 'DECRETO 93.872 / 1986', 'artigo' => '45', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 110, 'modalidade_id' => $ultimoCodigoItem - 1, 'ato_normativo' => 'DECRETO 93.872 / 1986', 'artigo' => '45', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 111, 'modalidade_id' => $ultimoCodigoItem - 1, 'ato_normativo' => 'DECRETO 93.872 / 1986', 'artigo' => '45', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 112, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 113, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 114, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 115, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 116, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 117, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 118, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 119, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 120, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 121, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 122, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'paragrafo' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 123, 'modalidade_id' => $ultimoCodigoItem - 2, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 13.303 / 2016', 'artigo' => '42', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 124, 'modalidade_id' => $ultimoCodigoItem - 3, 'ato_normativo' => 'ATO 09 / 1995', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 125, 'modalidade_id' => $ultimoCodigoItem - 3, 'ato_normativo' => 'LEI 9.472 / 1997', 'artigo' => '54', 'paragrafo' => 'ÚNICO', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['id' => 126, 'modalidade_id' => 76, 'ato_normativo' => 'LEI 10.520 / 2002', 'artigo' => '1', 'created_at' => now(), 'updated_at' => now()]);
    }
}
