<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdtransacaoContratoPublicacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratopublicacoes', function (Blueprint $table) {
            $table->renameColumn('texto_rtf','transacao_id');
            $table->renameColumn('hash','materia_id');
            $table->string('oficio_id')->nullable();
            $table->string('log')->nullable();

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
            $table->renameColumn('transacao_id','texto_rtf');
            $table->renameColumn('materia_id','hash');
            $table->dropColumn('transacao_id');
            $table->dropColumn('log');
        });
    }
}
