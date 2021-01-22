<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCodigoitemDescresOperacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::where([
            'descricao' => 'Operação item empenho'
        ])->first();

        $itens = [
            'INCLUSAO' => 'INCLUSAO',
            'REFORCO' => 'REFORÇO',
            'ANULACAO' => 'ANULAÇÃO',
            'CANCELAMENTO' => 'CANCELAMENTO',
            'NENHUMA' => 'NENHUMA',
        ];

        foreach ($itens as $index => $item) {
            $codigoItem = Codigoitem::where('descricao', $item)
                ->where('codigo_id', $codigo->id)->first();
            $codigoItem->descres = $index;
            $codigoItem->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
