<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdomiciliobancarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdomiciliobancario', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpredoc_id');
            $table->integer('banco')->nullable();
            $table->integer('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('tipo');
        });

        Schema::table('sfdomiciliobancario', function (Blueprint $table) {
            $table->foreign('sfpredoc_id')->references('id')->on('sfpredoc')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfdomiciliobancario');
    }
}
