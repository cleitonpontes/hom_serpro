<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Database\Seeder;

class ApropriacaoFaturaSeeder extends Seeder
{
    public function run()
    {
        $codigo = Codigo::firstOrCreate(
            [
                'descricao' => 'Fases da apropriação da fatura',
                'visivel' => true,
            ],
            [
                'created_at' => now(),
                'updated_at' => now()
            ]);

        Codigoitem::firstOrCreate(
            [
                'codigo_id' => $codigo->id,
                'descres' => 'ANDAMENTO',
                'descricao' => 'Em andamento',
            ],
            [
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        Codigoitem::firstOrCreate(
            [
                'codigo_id' => $codigo->id,
                'descres' => 'CONCLUIDA',
                'descricao' => 'Concluída',
            ],
            [
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
