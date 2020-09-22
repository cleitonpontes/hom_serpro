<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Database\Seeder;

class PeriodicidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codigo = Codigo::create([
            'descricao' => 'Periodicidade da Glosa',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'ANUAL',
            'descricao' => 'Anual'
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'MENSAL',
            'descricao' => 'Mensal'
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'SEMANAL',
            'descricao' => 'Semanal'
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'DIARIA',
            'descricao' => 'Diária'
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'UNICA',
            'descricao' => 'Única'
        ]);
    }
}
