<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class OrgaoUnidadeSeeder extends Seeder
{
    public function run()
    {
        DB::table('orgaossuperiores')->insert([
            'codigo' => '63000',
            'nome' => 'ADVOCACIA-GERAL DA UNIÃO',
            'situacao' => true
        ]);

        DB::table('orgaos')->insert([
            'orgaosuperior_id' => '1',
            'codigo' => '63000',
            'codigosiasg' => '20114',
            'nome' => 'ADVOCACIA-GERAL DA UNIÃO',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110161',
            'codigosiasg' => '110161',
            'nome' => 'Superin. de Adm. no Distrito Federal',
            'nomeresumido' => 'SAD/DF',
            'telefone' => '(61) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110592',
            'codigosiasg' => '110592',
            'nome' => 'Unidade de Atendimento em Minas Gerais',
            'nomeresumido' => 'UA/MG',
            'telefone' => '(31) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110102',
            'codigosiasg' => '110102',
            'nome' => 'Superin. de Adm. no Rio de Janeiro',
            'nomeresumido' => 'SAD/RJ',
            'telefone' => '(21) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110099',
            'codigosiasg' => '110099',
            'nome' => 'Superin. de Adm. em São Paulo',
            'nomeresumido' => 'SAD/SP',
            'telefone' => '(11) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110096',
            'codigosiasg' => '110096',
            'nome' => 'Superin. de Adm. em Pernambuco',
            'nomeresumido' => 'SAD/PE',
            'telefone' => '(81) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110097',
            'codigosiasg' => '110097',
            'nome' => 'Superin. de Adm. no Rio Grande do Sul',
            'nomeresumido' => 'SAD/RS',
            'telefone' => '(XX) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);
        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110060',
            'codigosiasg' => '110060',
            'nome' => 'Coord. de Orçamento e Finanças - CORFI',
            'nomeresumido' => 'CORFI',
            'telefone' => '(XX) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);
        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110062',
            'codigosiasg' => '110062',
            'nome' => 'Diretoria de Gestão de Pessoas',
            'nomeresumido' => 'DGEP',
            'telefone' => '(XX) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);
        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110581',
            'codigosiasg' => '110581',
            'nome' => 'Diárias e Passagens',
            'nomeresumido' => 'DIDIP',
            'telefone' => '(XX) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);

        DB::table('unidades')->insert([
            'orgao_id' => '1',
            'codigo' => '110156',
            'codigosiasg' => '110156',
            'nome' => 'Escola da Advocacia-Geral da União',
            'nomeresumido' => 'EAGU',
            'telefone' => '(XX) 3333-3333',
            'tipo' => 'E',
            'situacao' => true
        ]);


    }
}
