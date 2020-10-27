<?php

use Illuminate\Database\Seeder;

class ApropriacaofaseNewSeeder extends Seeder
{
    public function run()
    {
        DB::table('apropriacoes_fases')->insert(['id' => 99, 'fase' => 'Cancelada']);
    }
}
