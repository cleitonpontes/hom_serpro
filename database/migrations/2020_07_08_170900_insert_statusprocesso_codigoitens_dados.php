<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertStatusprocessoCodigoitensDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Status Processo',
            'visivel' => true
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'Andamento',
            'descricao' => 'Processo em andamento'
        ]);
        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'Finalizado',
            'descricao' => 'Processo finalizado'
        ]);
        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'Bloqueado',
            'descricao' => 'Processo bloqueado'
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
            'descricao' => 'Status Processo',
            'visivel' => false
        ])->forceDelete();
    }
}
