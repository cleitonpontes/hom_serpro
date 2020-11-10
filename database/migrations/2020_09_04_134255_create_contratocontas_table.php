<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateContratocontasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratocontas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->string('banco');
            $table->string('agencia');
            $table->string('conta_corrente');
            $table->string('fat_empresa');
            $table->timestamps();
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
        Schema::dropIfExists('contratocontas');
    }
}
