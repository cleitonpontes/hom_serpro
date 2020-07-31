<?php

use Illuminate\Database\Seeder;

class OrgaossuperioresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('orgaossuperiores')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'codigo' => '70000',
                'nome' => 'JUSTIÇA ELEITORAL',
                'situacao' => true,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => '2019-11-08 21:38:43',
            ),
        ));


    }
}
