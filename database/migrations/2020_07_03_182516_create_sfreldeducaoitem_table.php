<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfreldeducaoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfreldeducaoitem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfcompensacao_id')->nullable();
            $table->bigInteger('numseqitem')->nullable();;
        });

        Schema::table('sfreldeducaoitem', function (Blueprint $table) {
            $table->foreign('sfcompensacao_id')->references('id')->on('sfcompensacao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfreldeducaoitem', function (Blueprint $table) {
            Schema::dropIfExists('sfreldeducaoitem');
        });
    }
}
