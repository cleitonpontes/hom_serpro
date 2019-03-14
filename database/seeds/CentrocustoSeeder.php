<?php

use Illuminate\Database\Seeder;

class CentrocustoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('centrocusto')->insert([
            'codigo' => '000000',
            'descricao' => 'Não se aplica',
            'situacao' => 0
            ]);
    }
}
