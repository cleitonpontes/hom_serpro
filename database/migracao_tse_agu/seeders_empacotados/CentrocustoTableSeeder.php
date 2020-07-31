<?php

use Illuminate\Database\Seeder;

class CentrocustoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('centrocusto')->insert(array (
            0 => 
            array (
                'id' => 55000001,
                'codigo' => '000000',
                'descricao' => 'Não se aplica',
                'situacao' => false,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
