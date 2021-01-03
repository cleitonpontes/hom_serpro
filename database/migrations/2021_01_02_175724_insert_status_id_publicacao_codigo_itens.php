<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigo;
use App\Models\Codigoitem;

class InsertStatusIdPublicacaoCodigoItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::where([
            'descricao' => 'Situacao Publicacao',
            'visivel' => false
        ])->first();

        Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => '99',
            'descricao' => 'MATERIA SUSTADA',
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
        Codigoitem::where([
            'descricao' => 'MATERIA SUSTADA',
            'visivel' => false
        ])->forceDelete();
    }
}
