<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoterceirizadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoterceirizados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->string('cpf');
            $table->string('nome');
            $table->integer('funcao_id');
            $table->integer('jornada');
            $table->string('unidade');
            $table->decimal('salario');
            $table->decimal('custo');
            $table->integer('escolaridade_id');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->boolean('situacao');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('funcao_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('escolaridade_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratoterceirizados');
    }
}
