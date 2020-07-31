<?php

use Illuminate\Database\Seeder;

class ApropriacoesFasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('apropriacoes_fases')->insert(array (
            0 => 
            array (
                'id' => 55000000,
                'fase' => 'Não iniciada',
            ),
            1 => 
            array (
                'id' => 55000001,
                'fase' => 'Importar DDP',
            ),
            2 => 
            array (
                'id' => 55000002,
                'fase' => 'Identificar Situação',
            ),
            3 => 
            array (
                'id' => 55000003,
                'fase' => 'Identificar Empenhos',
            ),
            4 => 
            array (
                'id' => 55000004,
                'fase' => 'Validar Saldos',
            ),
            5 => 
            array (
                'id' => 55000005,
                'fase' => 'Informar Dados Complementares',
            ),
            6 => 
            array (
                'id' => 55000006,
                'fase' => 'Persistir Dados',
            ),
            7 => 
            array (
                'id' => 55000007,
                'fase' => 'Gerar XML',
            ),
            8 => 
            array (
                'id' => 55000008,
                'fase' => 'Finalizada',
            ),
        ));
        
        
    }
}
