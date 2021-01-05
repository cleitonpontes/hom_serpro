<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoHistoricoMinutaEmpenho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_historico_minuta_empenho', function (Blueprint $table) {
            $table->integer('contrato_historico_id')->nullable();
            $table->integer('minuta_empenho_id')->nullable();

            $table->foreign('contrato_historico_id')->references('id')->on('contratohistorico')->onDelete('cascade');
            $table->foreign('minuta_empenho_id')->references('id')->on('minutaempenhos')->onDelete('cascade');

            $table->unique(['contrato_historico_id', 'minuta_empenho_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contrato_historico_minuta_empenho');
    }
}
