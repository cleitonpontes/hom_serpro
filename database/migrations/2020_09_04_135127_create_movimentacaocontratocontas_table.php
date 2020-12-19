<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimentacaocontratocontasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimentacaocontratocontas', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('contratoconta_id');
            $table->foreign('contratoconta_id')->references('id')->on('contratocontas')->onDelete('cascade');

            $table->integer('tipo_id');
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');

            $table->string('mes_competencia');
            $table->string('ano_competencia');

            $table->decimal('valor_total_mes_ano',15,2)->nullable();

            $table->string('situacao_movimentacao');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('movimentacaocontratocontas');
    }
}
