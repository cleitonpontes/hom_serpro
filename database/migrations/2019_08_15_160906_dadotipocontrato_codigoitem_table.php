<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigoitem;

class DadotipocontratoCodigoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Codigoitem::create([
            'codigo_id' => 12,
            'descres' => '99',
            'descricao' => 'Empenho'
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
            'codigo_id' => 12,
            'descres' => '99',
            'descricao' => 'Empenho'
        ])->forceDelete();
    }
}
