<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfrelencargoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfrelencargos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfdespesaanularitem_id')->nullable();
            $table->bigInteger('numseqitem')->nullable();;
        });

        Schema::table('sfrelencargos', function (Blueprint $table) {
            $table->foreign('sfdespesaanularitem_id')->references('id')->on('sfdespesaanularitem')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfrelencargos', function (Blueprint $table) {
            Schema::dropIfExists('sfrelcredito');
        });
    }
}
