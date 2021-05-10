<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alter2DevolveMinutaSiasgAddColumnMensagemSiasg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->string('mensagem_siasg')->nullable(); //gravar retorno do WS
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->dropColumn('mensagem_siasg');
        });
    }
}
