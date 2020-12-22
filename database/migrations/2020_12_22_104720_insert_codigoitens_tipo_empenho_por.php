<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigo;
use App\Models\Codigoitem;

class InsertCodigoitensTipoEmpenhoPor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Tipo Empenho Por',
            'visivel' => false
        ]);

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'COM',
            'descricao' => 'Compra'
        ]);
        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'CON',
            'descricao' => 'Contrato'
        ]);
        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'SUP',
            'descricao' => 'Suprimento'
        ]);
        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'ALT',
            'descricao' => 'Alteração'
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
            'descricao' => 'Tipo Empenho Por',
            'visivel' => false
        ])->forceDelete();

    }
}
