<?php

use Illuminate\Database\Seeder;

class OrgaosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('orgaos')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'orgaosuperior_id' => 55000001,
                'codigo' => '11111',
                'codigosiasg' => '70001',
                'nome' => 'TRIBUNAL SUPERIOR ELEITORAL',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => '2019-11-08 21:47:23',
            ),
        ));


    }
}
