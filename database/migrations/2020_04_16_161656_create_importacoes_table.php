<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportacoesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_arquivo');
            $table->char('delimitador');
            $table->string('arquivos');
            $table->integer('contrato_id');
            $table->integer('situacao_id');
            $table->integer('tipo_id');
            $table->integer('unidade_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('situacao_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');
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
        Schema::dropIfExists('importacoes');
    }
}
