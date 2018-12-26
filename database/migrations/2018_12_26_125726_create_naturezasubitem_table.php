<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNaturezasubitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('naturezasubitem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('naturezadespesa_id');
            $table->string('codigo');
            $table->string('descricao');
            $table->boolean('situacao');
            $table->timestamps();

            $table->foreign('naturezadespesa_id')->references('id')->on('naturezadespesa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('naturezasubitem');
    }
}
