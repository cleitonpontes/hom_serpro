<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfitemempenhoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfitemempenho', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sforcempenhodado_id');
            $table->integer('numseqitem');
            $table->string('codsubelemento', 2);
            $table->string('descricao', 1248);
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
        Schema::dropIfExists('sfitemempenho');
    }
}
