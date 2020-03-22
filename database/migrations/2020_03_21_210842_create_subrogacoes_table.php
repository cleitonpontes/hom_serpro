<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubrogacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subrogacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unidadeorigem_id');
            $table->integer('contrato_id');
            $table->integer('unidadedestino_id');
            $table->date('data_termo');
            $table->timestamps();

            $table->foreign('unidadeorigem_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('unidadedestino_id')->references('id')->on('unidades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subrogacoes');
    }
}
