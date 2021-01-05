<?php

use Illuminate\Database\Seeder;
use App\Models\Apropriacaofases;

class ApropriacaofaseNewSeeder extends Seeder
{
    public function run()
    {
        $fases = Apropriacaofases::firstOrCreate(
            [
                'id' => '99',
                'fase' => 'Cancelada',
            ]
        );
    }
}
