<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ************************************************************
        // Regiões
        // ************************************************************
        $codigoLista = Codigo::create([
            'descricao' => 'Regiões do País',
            'visivel' => 1
        ])->id;

        $norte = Codigoitem::create([
            'codigo_id' => $codigoLista,
            'descres' => 'NORTE',
            'descricao' => 'Norte'
        ])->id;

        $nordeste = Codigoitem::create([
            'codigo_id' => $codigoLista,
            'descres' => 'NORDESTE',
            'descricao' => 'Nordeste'
        ])->id;

        $centroOeste = Codigoitem::create([
            'codigo_id' => $codigoLista,
            'descres' => 'CENTRO',
            'descricao' => 'Centro-Oeste'
        ])->id;

        $sudeste = Codigoitem::create([
            'codigo_id' => $codigoLista,
            'descres' => 'SUDESTE',
            'descricao' => 'Sudeste'
        ])->id;

        $sul = Codigoitem::create([
            'codigo_id' => $codigoLista,
            'descres' => 'SUL',
            'descricao' => 'Sul'
        ])->id;

        // ************************************************************
        // Estados
        // ************************************************************
        DB::table('estados')->insert([
            'id' => 12,
            'sigla' => 'AC',
            'nome' => 'Acre',
            'regiao_id' => $norte,
            'latitude' => '-8.77',
            'longitude' => '-70.55'
        ]);

        DB::table('estados')->insert([
            'id' => 27,
            'sigla' => 'AL',
            'nome' => 'Alagoas',
            'regiao_id' => $nordeste,
            'latitude' => '-9.62',
            'longitude' => '-36.82'
        ]);

        DB::table('estados')->insert([
            'id' => 16,
            'sigla' => 'AP',
            'nome' => 'Amapá',
            'regiao_id' => $norte,
            'latitude' => '1.41',
            'longitude' => '-51.77'
        ]);

        DB::table('estados')->insert([
            'id' => 13,
            'sigla' => 'AM',
            'nome' => 'Amazonas',
            'regiao_id' => $norte,
            'latitude' => '-3.47',
            'longitude' => '-65.1'
        ]);

        DB::table('estados')->insert([
            'id' => 29,
            'sigla' => 'BA',
            'nome' => 'Bahia',
            'regiao_id' => $nordeste,
            'latitude' => '-13.29',
            'longitude' => '-41.71'
        ]);

        DB::table('estados')->insert([
            'id' => 23,
            'sigla' => 'CE',
            'nome' => 'Ceará',
            'regiao_id' => $nordeste,
            'latitude' => '-5.2',
            'longitude' => '-39.53'
        ]);

        DB::table('estados')->insert([
            'id' => 53,
            'sigla' => 'DF',
            'nome' => 'Distrito Federal',
            'regiao_id' => $centroOeste,
            'latitude' => '-15.83',
            'longitude' => '-47.86'
        ]);

        DB::table('estados')->insert([
            'id' => 32,
            'sigla' => 'ES',
            'nome' => 'Espírito Santo',
            'regiao_id' => $sudeste,
            'latitude' => '-19.19',
            'longitude' => '-40.34'
        ]);

        DB::table('estados')->insert([
            'id' => 52,
            'sigla' => 'GO',
            'nome' => 'Goiás',
            'regiao_id' => $centroOeste,
            'latitude' => '-15.98',
            'longitude' => '-49.86'
        ]);

        DB::table('estados')->insert([
            'id' => 21,
            'sigla' => 'MA',
            'nome' => 'Maranhão',
            'regiao_id' => $nordeste,
            'latitude' => '-5.42',
            'longitude' => '-45.44'
        ]);

        DB::table('estados')->insert([
            'id' => 51,
            'sigla' => 'MT',
            'nome' => 'Mato Grosso',
            'regiao_id' => $centroOeste,
            'latitude' => '-12.64',
            'longitude' => '-55.42'
        ]);

        DB::table('estados')->insert([
            'id' => 50,
            'sigla' => 'MS',
            'nome' => 'Mato Grosso do Sul',
            'regiao_id' => $centroOeste,
            'latitude' => '-20.51',
            'longitude' => '-54.54'
        ]);

        DB::table('estados')->insert([
            'id' => 31,
            'sigla' => 'MG',
            'nome' => 'Minas Gerais',
            'regiao_id' => $sudeste,
            'latitude' => '-18.1',
            'longitude' => '-44.38'
        ]);

        DB::table('estados')->insert([
            'id' => 15,
            'sigla' => 'PA',
            'nome' => 'Pará',
            'regiao_id' => $norte,
            'latitude' => '-3.79',
            'longitude' => '-52.48'
        ]);

        DB::table('estados')->insert([
            'id' => 25,
            'sigla' => 'PB',
            'nome' => 'Paraíba',
            'regiao_id' => $nordeste,
            'latitude' => '-7.28',
            'longitude' => '-36.72'
        ]);

        DB::table('estados')->insert([
            'id' => 41,
            'sigla' => 'PR',
            'nome' => 'Paraná',
            'regiao_id' => $sul,
            'latitude' => '-24.89',
            'longitude' => '-51.55'
        ]);

        DB::table('estados')->insert([
            'id' => 26,
            'sigla' => 'PE',
            'nome' => 'Pernambuco',
            'regiao_id' => $nordeste,
            'latitude' => '-8.38',
            'longitude' => '-37.86'
        ]);

        DB::table('estados')->insert([
            'id' => 22,
            'sigla' => 'PI',
            'nome' => 'Piauí',
            'regiao_id' => $nordeste,
            'latitude' => '-6.6',
            'longitude' => '-42.28'
        ]);

        DB::table('estados')->insert([
            'id' => 33,
            'sigla' => 'RJ',
            'nome' => 'Rio de Janeiro',
            'regiao_id' => $sudeste,
            'latitude' => '-22.25',
            'longitude' => '-42.66'
        ]);

        DB::table('estados')->insert([
            'id' => 24,
            'sigla' => 'RN',
            'nome' => 'Rio Grande do Norte',
            'regiao_id' => $nordeste,
            'latitude' => '-5.81',
            'longitude' => '-36.59'
        ]);

        DB::table('estados')->insert([
            'id' => 43,
            'sigla' => 'RS',
            'nome' => 'Rio Grande do Sul',
            'regiao_id' => $sul,
            'latitude' => '-30.17',
            'longitude' => '-53.5'
        ]);

        DB::table('estados')->insert([
            'id' => 11,
            'sigla' => 'RO',
            'nome' => 'Rondônia',
            'regiao_id' => $norte,
            'latitude' => '-10.83',
            'longitude' => '-63.34'
        ]);

        DB::table('estados')->insert([
            'id' => 14,
            'sigla' => 'RR',
            'nome' => 'Roraima',
            'regiao_id' => $norte,
            'latitude' => '1.99',
            'longitude' => '-61.33'
        ]);

        DB::table('estados')->insert([
            'id' => 42,
            'sigla' => 'SC',
            'nome' => 'Santa Catarina',
            'regiao_id' => $sul,
            'latitude' => '-27.45',
            'longitude' => '-50.95'
        ]);

        DB::table('estados')->insert([
            'id' => 35,
            'sigla' => 'SP',
            'nome' => 'São Paulo',
            'regiao_id' => $sudeste,
            'latitude' => '-22.19',
            'longitude' => '-48.79'
        ]);

        DB::table('estados')->insert([
            'id' => 28,
            'sigla' => 'SE',
            'nome' => 'Sergipe',
            'regiao_id' => $nordeste,
            'latitude' => '-10.57',
            'longitude' => '-37.45'
        ]);

        DB::table('estados')->insert([
            'id' => 17,
            'sigla' => 'TO',
            'nome' => 'Tocantins',
            'regiao_id' => $norte,
            'latitude' => '-9.46',
            'longitude' => '-48.26'
        ]);

        // ************************************************************
        // Exceção
        // ************************************************************
        DB::table('estados')->insert([
            'id' => 99,
            'sigla' => 'EX',
            'nome' => 'Exterior'
        ]);

    }

}
