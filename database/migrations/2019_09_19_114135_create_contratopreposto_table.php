<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoprepostoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratopreposto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('user_id')->nullable();
            $table->string('cpf');
            $table->string('nome');
            $table->string('email');
            $table->string('telefonefixo')->nullable();
            $table->string('celular')->nullable();
            $table->string('doc_formalizacao')->nullable();
            $table->string('informacao_complementar')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->boolean('situacao')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratopreposto');
    }
}
