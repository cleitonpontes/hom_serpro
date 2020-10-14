<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfpassivoanteriorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfpassivoanterior', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sforcempenhodado_id');
            $table->string('codcontacontabil');
            $table->timestamps();

            $table->foreign('sforcempenhodado_id')->references('id')->on('sforcempenhodados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfpassivoanterior');
    }
}
