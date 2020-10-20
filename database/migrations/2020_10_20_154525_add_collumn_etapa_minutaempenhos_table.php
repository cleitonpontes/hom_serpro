<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumnEtapaMinutaEmpenhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->integer('etapa');
            $table->integer('tipo_minuta_empenho')->nullable();; //Original, Alteraçao, Contrato Continuado

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('minutaempenhos', function (Blueprint $table) {
            $table->dropColumn('etapa');
            $table->dropColumn('tipo_minuta_empenho');
        });
    }
}
