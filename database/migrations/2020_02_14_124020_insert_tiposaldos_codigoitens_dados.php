<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class InsertTiposaldosCodigoitensDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Tipo Saldo Itens',
            'visivel' => false
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'TSDINIHIST',
            'descricao' => 'Saldo Inicial Contrato Historico'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'TSDALTHIST',
            'descricao' => 'Saldo Alteracao Contrato Historico'
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
            'descricao' => 'Tipo Saldo Itens',
            'visivel' => false
        ])->forceDelete();
    }
}
