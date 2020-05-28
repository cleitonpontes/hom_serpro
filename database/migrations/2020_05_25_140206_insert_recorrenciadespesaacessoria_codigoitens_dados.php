<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigo;
use App\Models\Codigoitem;

class InsertRecorrenciadespesaacessoriaCodigoitensDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Recorrência Despesa Acessória',
            'visivel' => true
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'ANUAL',
            'descricao' => 'Anual'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'MENSAL',
            'descricao' => 'Mensal'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'UNICA',
            'descricao' => 'Única'
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Codigo::where([
            'descricao' => 'Recorrência Despesa Acessória',
            'visivel' => true
        ])->forceDelete();
    }
}
