<?php

use App\Models\Catmatsergrupo;
use App\Models\Catmatseritem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigoitem;

class InsertCatmatseritensSuprimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $grupo_servico = Catmatsergrupo::where([
            'descricao' => 'GRUPO GENERICO SERVICO'
        ])->first();
        $grupo_material = Catmatsergrupo::where([
            'descricao' => 'GRUPO GENERICO MATERIAIS'
        ])->first();

        Catmatseritem::updateOrCreate(
            ['descricao' => 'MATERIAL PARA SUPRIMENTO DE FUNDOS'],
            [
                'grupo_id' => $grupo_material->id,
                'codigo_siasg' => 9999999,
                'situacao' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        Catmatseritem::updateOrCreate(
            ['descricao' => 'SERVIÇO PARA SUPRIMENTO DE FUNDOS'],
            [
                'grupo_id' => $grupo_servico->id,
                'codigo_siasg' => 9999999,
                'situacao' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Codigoitem::where([
            'descricao' => 'MATERIAL PARA SUPRIMENTO DE FUNDOS'
        ])->forceDelete();

        Codigoitem::where([
            'descricao' => 'SERVIÇO PARA SUPRIMENTO DE FUNDOS'
        ])->forceDelete();

    }
}
