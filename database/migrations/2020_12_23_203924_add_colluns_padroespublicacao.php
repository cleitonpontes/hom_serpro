<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollunsPadroespublicacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('padroespublicacao', function (Blueprint $table) {
            $table->integer('identificador_norma_id')->nullable();
            $table->foreign('identificador_norma_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('padroespublicacao', function (Blueprint $table) {
            $table->dropColumn('identificador_norma_id');
        });
    }
}
