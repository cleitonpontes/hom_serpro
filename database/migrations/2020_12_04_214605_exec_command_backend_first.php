<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExecCommandBackendFirst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //atualiza arquivos com composer
//        exec('composer dumpautoload');

        //executa seed atualização
//        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'UpdateVersion520Seeder'));

        //executa comando para sanitizar usuarios
//        \Illuminate\Support\Facades\Artisan::call('command:SanitizarUsuarioInativos');
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
