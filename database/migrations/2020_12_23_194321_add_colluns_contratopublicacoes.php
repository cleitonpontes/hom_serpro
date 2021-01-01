<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollunsContratopublicacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratopublicacoes', function (Blueprint $table) {
            $table->string('motivo_devolucao')->nullable();
            $table->integer('motivo_isencao_id')->nullable();
            $table->integer('tipo_pagamento_id')->nullable();
            $table->integer('pagina_publicacao')->nullable();
            $table->integer('secao_jornal')->nullable()->default(3);
            $table->string('link_publicacao')->nullable();

            $table->foreign('motivo_isencao_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('tipo_pagamento_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratopublicacoes', function (Blueprint $table) {
            $table->dropColumn('motivo_devolucao');
            $table->dropColumn('motivo_isencao_id');
            $table->dropColumn('tipo_pagamento_id');
            $table->dropColumn('pagina_publicacao');
            $table->dropColumn('secao_jornal');
            $table->dropColumn('link_publicacao');
        });
    }
}
