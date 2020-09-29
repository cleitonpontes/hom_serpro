<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmparoLegalRestricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amparo_legal_restricoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amparo_legal_id');
            $table->integer('tipo_restricao_id');
            $table->string('codigo_restricao');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('amparo_legal_id')->references('id')->on('amparo_legal');
            $table->foreign('tipo_restricao_id')->references('id')->on('codigoitens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amparo_legal_restricoes');
    }
}
