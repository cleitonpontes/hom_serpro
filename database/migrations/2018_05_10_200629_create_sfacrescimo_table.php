<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfacrescimoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfacrescimo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfded_id');
            $table->string('tpacrescimo')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->string('numempe')->nullable();
            $table->integer('codsubitemempe')->nullable();
            $table->string('txtinscra')->nullable();
            $table->integer('numclassa')->nullable();
            $table->string('txtinscrb')->nullable();
            $table->integer('numclassb')->nullable();
            $table->string('tipo');
        });

        Schema::table('sfacrescimo', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao_encargo_dadospagto')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfacrescimo');
    }
}
