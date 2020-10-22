<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepactuacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repactuacoes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('contratoconta_id');
            $table->foreign('contratoconta_id')->references('id')->on('contratocontas')->onDelete('cascade');

            $table->integer('movimentacao_id');
            $table->foreign('movimentacao_id')->references('id')->on('movimentacaocontratocontas')->onDelete('cascade');

            $table->integer('funcao_id');
            $table->foreign('funcao_id')->references('id')->on('codigoitens')->onDelete('cascade');

            $table->decimal('salario_novo',15,2)->nullable();
            // $table->decimal('salario_antigo',15,2)->nullable();    //não pode ter salario antigo, pois ele pode variar.

            $table->integer('jornada');

            $table->integer('mes_inicio');
            $table->integer('ano_inicio');

            $table->integer('mes_fim');
            $table->integer('ano_fim');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repactuacoes');
    }
}
