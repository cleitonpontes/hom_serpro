<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfrelitemvlrccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfrelitemvlrcc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfcc_id');
            $table->bigInteger('numseqpai')->nullable();
            $table->bigInteger('numseqitem')->nullable();
            $table->integer('codnatdespdet')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->string('tipo');
        });

        Schema::table('sfrelitemvlrcc', function (Blueprint $table) {
            $table->foreign('sfcc_id')->references('id')->on('sfcentrocusto')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfrelitemvlrcc');
    }
}
