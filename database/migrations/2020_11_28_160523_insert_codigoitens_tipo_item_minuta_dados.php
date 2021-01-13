<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigo;
use App\Models\Codigoitem;


class InsertCodigoitensTipoItemMinutaDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Operação item empenho',
            'visivel' => true
        ]);
        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'INCLUS',
            'descricao' => 'INCLUSAO'
        ]);
        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'REFOR',
            'descricao' => 'REFORÇO'
        ]);
        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'ANULA',
            'descricao' => 'ANULAÇÃO'
        ]);
        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'CANCEL',
            'descricao' => 'CANCELAMENTO'
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
            'descricao' => 'Tipo Feriados',
            'visivel' => true
        ])->forceDelete();
    }
}
