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
            $table->integer('restricao_id');
            $table->integer('tipo_restricao_id');
            $table->string('codigo_restricao');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('restricao_id')->references('id')->on('amparo_legal');
            $table->foreign('tipo_restricao_id')->references('id')->on('codigoitens'); // Código Itens = xx
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
