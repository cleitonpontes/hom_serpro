<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfparcelaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfparcela', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfcronbaixapatrimonial_id');
            $table->bigInteger('numparcela')->nullable();
            $table->date('dtprevista')->nullable();
            $table->decimal('vlr')->nullable();
        });

        Schema::table('sfparcela', function (Blueprint $table) {
            $table->foreign('sfcronbaixapatrimonial_id')->references('id')->on('sfcronbaixapatrimonial')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfparcela', function (Blueprint $table) {
            //
        });
    }
}
