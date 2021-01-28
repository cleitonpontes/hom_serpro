<?php

use App\Models\Codigo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigoitem;

class UpdateDescresSuprimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::where([
            'descricao' => 'Modalidade Licitação'
        ])->first();

        Codigoitem::updateOrCreate(
            ['descricao' => 'Suprimento de Fundos'],
            [
                'descres' => '09',
                'codigo_id' => $codigo->id,
                'created_at' => now(),
                'updated_at' => now(),
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
        $codigo = Codigo::where([
            'descricao' => 'Modalidade Licitação'
        ])->first();

        Codigoitem::updateOrCreate(
            ['descricao' => 'Suprimento de Fundos'],
            [
                'descres' => 'SUPRIMENTO',
                'codigo_id' => $codigo->id,
                'created_at' => now(),
                'updated_at' => now(),
                'visivel' => false
            ]);
    }
}
