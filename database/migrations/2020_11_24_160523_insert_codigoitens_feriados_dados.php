<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigo;
use App\Models\Codigoitem;


class InsertCodigoitensFeriadosDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Tipo Feriados',
            'visivel' => true
        ]);
        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'FERNAC',
            'descricao' => 'Feriado Nacional'
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
