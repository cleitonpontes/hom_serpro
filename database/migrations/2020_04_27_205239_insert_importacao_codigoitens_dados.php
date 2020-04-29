<?php

use App\Models\Codigo;
use App\Models\Codigoitem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertImportacaoCodigoitensDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::create([
            'descricao' => 'Tipo Importação',
            'visivel' => true
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'TCZ',
            'descricao' => 'Terceirizado'
        ]);

        $codigo = Codigo::create([
            'descricao' => 'Situação Arquivo',
            'visivel' => true
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'PEXEC',
            'descricao' => 'Pendente de Execução'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'EEXEC',
            'descricao' => 'Erro de Execução'
        ]);

        $codigoitem = Codigoitem::create([
            'codigo_id' => $codigo->id,
            'descres' => 'EXECUTADO',
            'descricao' => 'Executado'
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
            'descricao' => 'Tipo Importação',
            'visivel' => false
        ])->forceDelete();

        Codigo::where([
            'descricao' => 'Situação Arquivo',
            'visivel' => false
        ])->forceDelete();
    }
}
