<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratofaturaEmpenhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratofatura_empenhos', function (Blueprint $table) {
            $table->integer('contratofatura_id')->unsigned();
            $table->integer('empenho_id')->unsigned();
            $table->foreign('contratofatura_id')->references('id')->on('contratofaturas')->onDelete('cascade');
            $table->foreign('empenho_id')->references('id')->on('empenhos')->onDelete('cascade');
            $table->primary(['contratofatura_id','empenho_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratofatura_empenhos');
    }
}
