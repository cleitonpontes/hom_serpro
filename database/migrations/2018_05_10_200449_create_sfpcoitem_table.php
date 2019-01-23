<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfpcoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfpcoitem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpco_id');
            $table->bigInteger('numseqitem');
            $table->string('numempe');
            $table->integer('codsubitemempe');
            $table->boolean('indrliquidado');
            $table->decimal('vlr',15,2);
            $table->string('txtinscra');
            $table->integer('numclassa');
            $table->string('txtinscrb');
            $table->integer('numclassb');
            $table->string('txtinscrc');
            $table->integer('numclassc');
        });

        Schema::table('sfpcoitem', function (Blueprint $table) {
            $table->foreign('sfpco_id')->references('id')->on('sfpco')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfpcoitem');
    }
}
