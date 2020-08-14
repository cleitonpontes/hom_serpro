<?php

use Illuminate\Database\Seeder;

class ComunicaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('comunica')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'unidade_id' => 55000001,
                'role_id' => 55000002,
                'assunto' => 'DFGDFGDF',
                'mensagem' => '<p>dfgdfgdfg</p>',
                'anexos' => '[]',
                'situacao' => 'P',
                'deleted_at' => '2019-11-13 20:22:33',
                'created_at' => '2019-11-13 19:32:32',
                'updated_at' => '2019-11-13 20:22:33',
                'orgao_id' => NULL,
            ),
        ));


    }
}
