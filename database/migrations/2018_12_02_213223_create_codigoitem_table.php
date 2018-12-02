<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodigoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigoitens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo_id')->unsigned();
            $table->string('descres', 10);
            $table->string('descricao', 100);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('codigo_id')->references('id')->on('codigos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codigoitens');
    }
}
