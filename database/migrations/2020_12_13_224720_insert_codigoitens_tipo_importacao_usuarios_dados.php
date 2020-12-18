<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Codigo;
use App\Models\Codigoitem;

class InsertCodigoitensTipoImportacaoUsuariosDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::where('descricao', 'Tipo Importação')
            ->first();

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'USERS',
            'descricao' => 'Usuários'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Codigoitem::whereHas('codigo', function ($q){
            $q->where('descricao', 'Tipo Importação');
        })
        ->where([
            'descres' => 'USERS',
            'descricao' => 'Usuários'
        ])->forceDelete();
    }
}
