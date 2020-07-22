<?php

use Illuminate\Database\Seeder;

class TipolistafaturaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('tipolistafatura')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'nome' => 'FORNECIMENTO DE BENS',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 55000002,
                'nome' => 'LOCAÇÕES',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 55000003,
                'nome' => 'PRESTAÇÃO DE SERVIÇOS',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 55000004,
                'nome' => 'REALIZAÇÃO DE OBRAS',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 55000005,
            'nome' => 'PEQUENOS CREDORES (Inciso II, 24, 8.666 e paragrafo 1º)',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 55000006,
                'nome' => 'VINCULAÇÕES ESPECÍFICAS',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}
