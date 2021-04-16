<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Codigo;
use App\Models\Codigoitem;



class AlterCodigoitensTipomovimentacaoContratoconta extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $objCodigo = Codigo::where([
            'descricao' => 'Tipo Movimentação',
        ])->first();

        $idCodigo = $objCodigo->id;

        $codigoitem = Codigoitem::create([
            'codigo_id' => $idCodigo,
            'descres' => 'PROV',
            'descricao' => 'Provisão'
        ]);
        $codigoitem = Codigoitem::create([
            'codigo_id' => $idCodigo,
            'descres' => 'LIB',
            'descricao' => 'Liberação'
        ]);


        Codigoitem::where('descricao','=','Depósito')->where('descres', '=', 'DEP')->where('codigo_id', '=', $idCodigo)->delete();
        Codigoitem::where('descricao','=','Retirada')->where('descres', '=', 'RET')->where('codigo_id', '=', $idCodigo)->delete();



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
