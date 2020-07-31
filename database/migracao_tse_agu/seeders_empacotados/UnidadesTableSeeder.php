<?php

use Illuminate\Database\Seeder;

class UnidadesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('unidades')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'orgao_id' => 55000001,
                'codigo' => '700001',
                'gestao' => '00001',
                'codigosiasg' => '700001',
                'nome' => 'SECRETARIA DE ADMINISTRAÇÃO - TSE',
                'nomeresumido' => 'SAD',
            'telefone' => '(61) 3030-8060',
                'tipo' => 'E',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => '2019-11-13 18:53:28',
            ),
        ));


    }
}
