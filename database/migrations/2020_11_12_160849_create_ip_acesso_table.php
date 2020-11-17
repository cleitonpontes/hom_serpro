<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpAcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ipsacesso', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('orgao_id');
            $table->bigInteger('unidade_id');
            $table->json('ips');

            $table->foreign('orgao_id')->references('id')->on('orgaos')->onDelete('cascade');
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ipsacesso');
    }
}
