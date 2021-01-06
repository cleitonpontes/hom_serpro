<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotivoIsencaoCodigoItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::where([
            'descricao' => 'Motivo Isenção',
            'visivel' => false
        ])->first();

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => '0',
            'descricao' => 'Indefinido',
            'visivel' => true
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
            'descricao' => 'Motivo Isenção',
            'visivel' => false
        ])->first();

        Codigoitem::where([
            'codigo_id' => $codigo->id,
            'descricao' => 'Indefinido',
            'visivel' => true
        ])->forceDelete();
    }
}
