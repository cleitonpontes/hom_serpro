<?php

use App\Models\Codigo;
use Illuminate\Database\Seeder;

class AmparoLegalSeeder extends Seeder
{
    public function run()
    {
        $itemRegimeDiferenciado = 160;
        $itemNaoSeAplica = 172;

        DB::table('codigoitens')->insert([
            'codigo_id' => Codigo::MODALIDADE_LICITACAO,
            'descres' => 'CONSULTA',
            'descricao' => 'Consulta',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $itemConsulta = \App\Models\Codigoitem::all()->last()->id;

        DB::table('codigoitens')->insert([
            'codigo_id' => 13,
            'descres' => 'SUPRIMENTO',
            'descricao' => 'Suprimento de Fundos',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $itemSuprimentoDeFundos = \App\Models\Codigoitem::all()->last()->id;

        // Concurso
        DB::table('amparo_legal')->insert(['codigo' => 1, 'modalidade_id' => 72, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '22', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        // Convite
        DB::table('amparo_legal')->insert(['codigo' => 2, 'modalidade_id' => 73, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 3, 'modalidade_id' => 73, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        // Tomada de Preços
        DB::table('amparo_legal')->insert(['codigo' => 4, 'modalidade_id' => 77, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 5, 'modalidade_id' => 77, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        // Concorrência
        DB::table('amparo_legal')->insert(['codigo' => 6, 'modalidade_id' => 71, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 7, 'modalidade_id' => 71, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '23', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 8, 'modalidade_id' => 71, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '42', 'created_at' => now(), 'updated_at' => now()]);
        // Dispensa
        DB::table('amparo_legal')->insert(['codigo' => 9, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '17', 'paragrafo' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 10, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 11, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 12, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 13, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 14, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 15, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 16, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 17, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 18, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 19, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 20, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 21, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 22, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 23, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 24, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 25, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 26, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 27, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 28, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XIX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 29, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 30, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 31, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 32, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 33, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 34, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 35, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 36, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 37, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 38, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXIX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 39, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 40, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 41, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 42, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 43, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 44, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '24', 'inciso' => 'XXXV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 45, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 10.847 / 2004', 'artigo' => '6', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 46, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.652 / 2008', 'artigo' => '8', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 47, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.652 / 2008', 'artigo' => '8', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 48, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.947 / 2009', 'artigo' => '14', 'paragrafo' => '1', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 49, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.512 / 2011', 'artigo' => '17', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 50, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.663 / 2012', 'artigo' => '55', 'paragrafo' => '2', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 51, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.865 / 2013', 'artigo' => '18', 'paragrafo' => '1', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 52, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 12.873 / 2013', 'artigo' => '42', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 53, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 54, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 55, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 56, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 57, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 58, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 59, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 60, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 61, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 62, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 63, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 64, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 65, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 66, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 67, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 68, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 69, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 70, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 71, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 72, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 73, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 74, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 75, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 76, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 77, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 78, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 79, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 80, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XIV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 81, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 82, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 83, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 84, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 85, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 86, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 87, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 88, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '29', 'inciso' => 'XVIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 89, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '08', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 90, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '27', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 91, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 11.759 / 2008', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 92, 'modalidade_id' => 74, 'ato_normativo' => 'LEI 13.979 / 2020', 'artigo' => '04', 'created_at' => now(), 'updated_at' => now()]);
        // Inexigibilidade
        DB::table('amparo_legal')->insert(['codigo' => 93, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 94, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 95, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 96, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 8.666 / 1993', 'artigo' => '25', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 97, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 98, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 99, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 100, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 101, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 102, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 13.303 / 2016', 'artigo' => '30', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 103, 'modalidade_id' => 75, 'ato_normativo' => 'LEI 11.652 / 2008', 'artigo' => '27', 'created_at' => now(), 'updated_at' => now()]);
        // Não se aplica
        DB::table('amparo_legal')->insert(['codigo' => 104, 'modalidade_id' => $itemNaoSeAplica, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 105, 'modalidade_id' => $itemNaoSeAplica, 'ato_normativo' => 'ATO 09 / 1995', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 106, 'modalidade_id' => $itemNaoSeAplica, 'ato_normativo' => 'ATO 15 / 1997', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 107, 'modalidade_id' => $itemNaoSeAplica, 'ato_normativo' => 'PR 253.801 / 2001', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 108, 'modalidade_id' => $itemNaoSeAplica, 'ato_normativo' => 'TCU 61.099 / 1967', 'created_at' => now(), 'updated_at' => now()]);
        // Suprimento
        DB::table('amparo_legal')->insert(['codigo' => 109, 'modalidade_id' => $itemSuprimentoDeFundos, 'ato_normativo' => 'DECRETO 93.872 / 1986', 'artigo' => '45', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 110, 'modalidade_id' => $itemSuprimentoDeFundos, 'ato_normativo' => 'DECRETO 93.872 / 1986', 'artigo' => '45', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 111, 'modalidade_id' => $itemSuprimentoDeFundos, 'ato_normativo' => 'DECRETO 93.872 / 1986', 'artigo' => '45', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        // Regime diferenciado
        DB::table('amparo_legal')->insert(['codigo' => 112, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'I', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 113, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'II', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 114, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'III', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 115, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'IV', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 116, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'V', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 117, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'VI', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 118, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'VII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 119, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'VIII', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 120, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'IX', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 121, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'inciso' => 'X', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 122, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 12.462 / 2011', 'artigo' => '1', 'paragrafo' => '3', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 123, 'modalidade_id' => $itemRegimeDiferenciado, 'ato_normativo' => 'CONTRATAÇÃO PÚBLICA LEI 13.303 / 2016', 'artigo' => '42', 'created_at' => now(), 'updated_at' => now()]);
        // Consulta
        DB::table('amparo_legal')->insert(['codigo' => 124, 'modalidade_id' => $itemConsulta, 'ato_normativo' => 'ATO 09 / 1995', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('amparo_legal')->insert(['codigo' => 125, 'modalidade_id' => $itemConsulta, 'ato_normativo' => 'LEI 9.472 / 1997', 'artigo' => '54', 'paragrafo' => 'ÚNICO', 'created_at' => now(), 'updated_at' => now()]);
        // Pregão
        DB::table('amparo_legal')->insert(['codigo' => 126, 'modalidade_id' => 76, 'ato_normativo' => 'LEI 10.520 / 2002', 'artigo' => '1', 'created_at' => now(), 'updated_at' => now()]);
    }
}
