<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfrelcreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfrelcredito', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfdeducao_id')->nullable();
            $table->bigInteger('numseqitem')->nullable();;
        });

        Schema::table('sfrelcredito', function (Blueprint $table) {
            $table->foreign('sfdeducao_id')->references('id')->on('sfdeducao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfrelcredito', function (Blueprint $table) {
            Schema::dropIfExists('sfrelcredito');
        });
    }
}
