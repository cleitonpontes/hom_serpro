<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigo;
use App\Models\Codigoitem;

class InsertTipodespesaacessoriaCodigoitensDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Tipo Despesa Acessória',
            'visivel' => true
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'CONDOMINIO',
            'descricao' => 'Condomínio'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'TRIBUTO',
            'descricao' => 'Tributo'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'TAXA',
            'descricao' => 'Taxa'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'SEGURO',
            'descricao' => 'Seguro'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'GARANTIA',
            'descricao' => 'Garantia Extendida'
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
            'descricao' => 'Tipo Despesa Acessória',
            'visivel' => true
        ])->forceDelete();
    }
}
