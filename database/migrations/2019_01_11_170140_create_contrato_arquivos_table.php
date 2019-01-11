<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoArquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_arquivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('tipo');
            $table->string('descricao')->nullable();
            $table->string('arquivos');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('tipo')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contrato_arquivos');
    }
}
