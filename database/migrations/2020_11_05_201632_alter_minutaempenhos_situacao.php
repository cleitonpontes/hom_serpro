<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMinutaempenhosSituacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minutaempenhos', function (Blueprint $table) {

            $table->dropColumn('situacao');
            $table->integer('situacao_id');
            $table->foreign('situacao_id')->references('id')->on('codigoitens')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->dropColumn(['unidadeorigem_id']);
            $table->string('situacao')->default('Em andamento');
        });

    }
}
