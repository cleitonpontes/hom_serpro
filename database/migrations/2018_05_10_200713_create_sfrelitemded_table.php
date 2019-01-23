<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfrelitemdedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfrelitemded', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfded_id');
            $table->integer('numseqpai')->nullable();
            $table->integer('numseqitem')->nullable();
            $table->string('tipo');
        });

        Schema::table('sfrelitemded', function (Blueprint $table) {
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
        Schema::dropIfExists('sfrelitemded');
    }
}
