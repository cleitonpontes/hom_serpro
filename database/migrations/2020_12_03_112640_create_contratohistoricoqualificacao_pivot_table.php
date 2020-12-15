<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoHistoricoQualificacaoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratohistoricoqualificacao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contratohistorico_id')->unsigned()->index();
            $table->foreign('contratohistorico_id')->references('id')->on('contratohistorico')->onDelete('cascade');
            $table->integer('tipo_id')->unsigned()->index();
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratohistoricoqualificacao');
    }
}
