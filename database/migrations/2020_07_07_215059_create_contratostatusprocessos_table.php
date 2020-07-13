<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratostatusprocessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratostatusprocessos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->string('processo');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->string('status');
            $table->string('unidade');
            $table->integer('situacao');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('situacao')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratostatusprocessos');
    }
}
