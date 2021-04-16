<?php

use App\Models\Codigo;
use App\Models\Codigoitem;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class InsertCodigoitensEncargosDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Tipo Encargos',
            'visivel' => true
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => '13o.',
            'descricao' => 'Décimo Terceiro Salário'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'FÉRIAS',
            'descricao' => 'Férias e Adicional'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'RESCISÃO',
            'descricao' => 'Rescisão e Adicional do FGTS'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'GRUPO A',
            'descricao' => 'Grupo "A" sobre 13o. Salário e Férias'
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
            'descricao' => 'Tipo Encargos',
            'visivel' => false
        ])->forceDelete();

    }
}
