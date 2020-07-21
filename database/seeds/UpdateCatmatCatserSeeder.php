<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UpdateCatmatCatserSeederTableSeeder extends Seeder
{
    public function run()
    {
        //Atualiza Itens Material
        $cogigoitem = \App\Models\Codigoitem::whereHas('codigo', function ($q) {
            $q->where('descricao', 'Tipo CATMAT e CATSER');
        })
            ->where('descres', 'MATERIAL')
            ->first();

        $grupo = \App\Models\Catmatsergrupo::create([
            'tipo_id' => $cogigoitem->id,
            'descricao' => 'GRUPO GENERICO MATERIAIS'
        ]);

        $itens = \App\Models\Catmatseritem::whereHas('catmatsergrupo', function ($g) {
            $g->whereHas('tipo', function ($t) {
                $t->whereHas('codigo', function ($q) {
                    $q->where('descricao', 'Tipo CATMAT e CATSER');
                })
                    ->where('descres', 'MATERIAL');
            });
        })
            ->update(['grupo_id' => $grupo->id]);

        //Atualiza Itens Serviços
        $cogigoitem = \App\Models\Codigoitem::whereHas('codigo', function ($q) {
            $q->where('descricao', 'Tipo CATMAT e CATSER');
        })
            ->where('descres', 'SERVIÇO')
            ->first();

        $grupo = \App\Models\Catmatsergrupo::create([
            'tipo_id' => $cogigoitem->id,
            'descricao' => 'GRUPO GENERICO SERVICO'
        ]);

        $itens = \App\Models\Catmatseritem::whereHas('catmatsergrupo', function ($g) {
            $g->whereHas('tipo', function ($t) {
                $t->whereHas('codigo', function ($q) {
                    $q->where('descricao', 'Tipo CATMAT e CATSER');
                })
                    ->where('descres', 'SERVIÇO');
            });
        })
            ->update(['grupo_id' => $grupo->id]);

    }
}
