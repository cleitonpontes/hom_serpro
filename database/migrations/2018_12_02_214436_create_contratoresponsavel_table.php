<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoresponsavelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoresponsaveis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('user_id');
            $table->integer('funcao_id');
            $table->integer('instalacao_id')->nullable();
            $table->string('portaria');
            $table->boolean('situacao');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('funcao_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('instalacao_id')->references('id')->on('instalacoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratoresponsaveis');
    }
}
