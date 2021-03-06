<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFlagVisibleCodigoItem extends Migration
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

        //executa seed atualização
        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'UpdateCodigoItemFlagVisibleByDescresSeeder'));

        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'TipoQualificacaoSeeder'));

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
