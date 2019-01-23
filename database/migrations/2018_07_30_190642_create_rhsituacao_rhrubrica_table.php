<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhsituacaoRhrubricaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rhsituacao_rhrubrica', function (Blueprint $table) {
            $table->integer('rhsituacao_id')->unsigned();
            $table->integer('rhrubrica_id')->unsigned();
            $table->foreign('rhsituacao_id')->references('id')->on('rhsituacao')->onDelete('cascade');
            $table->foreign('rhrubrica_id')->references('id')->on('rhrubrica')->onDelete('cascade');
            $table->primary(['rhsituacao_id','rhrubrica_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rhsituacao_rhrubrica');
    }
}
