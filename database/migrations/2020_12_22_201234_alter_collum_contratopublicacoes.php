<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollumContratopublicacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratopublicacoes', function (Blueprint $table) {
            $table->dropColumn('situacao');
            $table->dropColumn('publicar');
            $table->integer('status_publicacao_id')->nullable();

            $table->foreign('status_publicacao_id')->references('id')->on('codigoitens')->onDelete('cascade');
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
            $table->dropColumn('status_publicacao_id')->change();
            $table->boolean('publicar')->nullable()->default(true);
            $table->string('situacao')->nullable();
        });
    }
}
