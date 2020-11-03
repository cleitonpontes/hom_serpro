<?php

use App\Models\Codigo;
use App\Models\Codigoitem;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCodigoitensTipomovimentacaoContratocontaDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $codigo = Codigo::create([
            'descricao' => 'Tipo Movimentação',
            'visivel' => true
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'DEP',
            'descricao' => 'Depósito'
        ]);
        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'RET',
            'descricao' => 'Retirada'
        ]);
        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'REPCT',
            'descricao' => 'Repactuação'
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
            'descricao' => 'Tipo Movimentação',
            'visivel' => false
        ])->forceDelete();
    }
}
