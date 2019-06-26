<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatmatsergruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catmatsergrupos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_id');
            $table->text('descricao');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catmatsergrupos');
    }
}
