<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Database\Seeder;

class EscopoGlosaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codigo = Codigo::create([
            'descricao' => 'Escopo da Glosa',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'SERVICO',
            'descricao' => 'Serviço'
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'FATURA',
            'descricao' => 'Fatura'
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'CONTRATO',
            'descricao' => 'Contrato'
        ]);

    }
}
