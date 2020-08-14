<?php

use Illuminate\Database\Seeder;

class InstalacoesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('instalacoes')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'nome' => 'TSE - Sede',
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 55000002,
                'nome' => 'TSE - Anexo',
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}
