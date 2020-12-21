<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePadraopublicacaoCodigoitem extends Migration
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

        //executa seed tipo de mudança  - Publicação
        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'TipoMudancaSeed'));
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
