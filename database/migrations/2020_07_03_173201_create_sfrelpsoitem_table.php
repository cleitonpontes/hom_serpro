<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfrelpsoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfrelpsoitem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfded_id')->nullable();
            $table->bigInteger('numseqpai')->nullable();
            $table->bigInteger('numseqitem')->nullable();;
        });

        Schema::table('sfrelpsoitem', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfrelpsoitem', function (Blueprint $table) {
            Schema::dropIfExists('sfrelpsoitem');
        });
    }
}
