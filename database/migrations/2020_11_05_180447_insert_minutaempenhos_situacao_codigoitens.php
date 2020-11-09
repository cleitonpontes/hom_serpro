<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMinutaempenhosSituacaoCodigoitens extends Migration
{
    //TODO MIGRAR PARA SEEDS
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Situações Minuta Empenho',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'ANDAMENTO',
            'descricao' => 'EM ANDAMENTO',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'PROCESSAME',
            'descricao' => 'EM PROCESSAMENTO',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'ERRO',
            'descricao' => 'ERRO',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'EMITIDO',
            'descricao' => 'EMPENHO EMITIDO',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'CANCELADO',
            'descricao' => 'EMPENHO CANCELADO',
            'visivel' => false
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
            'descricao' => 'Situações Minuta Empenho',
            'visivel' => false
        ])->forceDelete();
    }
}
