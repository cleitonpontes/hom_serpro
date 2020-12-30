<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCodigosCodigoitensNormaIsencaoPagamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //atualiza arquivos com composer
        exec('composer dumpautoload');

        //executa seed inserção de codigos e codigoitens
        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'TipoNormaPublicacaoSeeder'));

        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'MotivoIsencaoSeeder'));

        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'FormaPagamentoSeeder'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
