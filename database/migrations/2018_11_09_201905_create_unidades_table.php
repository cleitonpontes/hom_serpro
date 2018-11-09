<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orgao_id');
            $table->string('codigo')->unique();
            $table->string('codigosiasg')->nullable();;
            $table->string('nome');
            $table->string('nomeresumido');
            $table->string('telefone')->nullable();;
            $table->string('tipo');
            $table->string('situacao');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('orgao_id')->references('id')->on('orgaos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidades');
    }
}
