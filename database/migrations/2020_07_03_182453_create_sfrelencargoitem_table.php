<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfrelencargoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfrelencargoitem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfcompensacao_id')->nullable();
            $table->bigInteger('numseqitem')->nullable();;
        });

        Schema::table('sfrelencargoitem', function (Blueprint $table) {
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
        Schema::table('sfrelencargoitem', function (Blueprint $table) {
            Schema::dropIfExists('sfrelencargoitem');
        });
    }
}
