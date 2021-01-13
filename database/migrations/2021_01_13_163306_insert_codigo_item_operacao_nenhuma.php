<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCodigoItemOperacaoNenhuma extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::where([
            'descricao' => 'Operação item empenho'
        ])->first();
        $codigo->visivel = false;
        $codigo->save();

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'NENH',
            'descricao' => 'NENHUMA',
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
        $codigo = Codigo::where([
            'descricao' => 'Operação item empenho'
        ])->first();
        $codigo->visivel = true;
        $codigo->save();

        Codigoitem::where([
            'codigo_id' => $codigo->id,
            'descricao' => 'NENHUMA',
        ])->forceDelete();
    }
}
