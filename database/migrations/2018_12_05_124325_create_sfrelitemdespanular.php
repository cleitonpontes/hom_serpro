<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfrelitemdespanular extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfrelitemdespanular', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfdespanular_id');
            $table->integer('numseqpai')->nullable();
            $table->integer('numseqitem')->nullable();
            $table->decimal('vlr')->nullable();
        });

        Schema::table('sfrelitemdespanular', function (Blueprint $table) {
            $table->foreign('sfdespanular_id')->references('id')->on('sfdespesaanularitem')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfrelitemdespanular');
    }
}
