<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhsituacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rhsituacao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('execsfsituacao_id');
            $table->string('nd');
            $table->string('nddesc');
            $table->string('vpd');
            $table->string('vpddesc');
            $table->string('ddp_nivel');
            $table->boolean('status');
            $table->timestamps();
            $table->foreign('execsfsituacao_id')->references('id')->on('execsfsituacao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rhsituacao');
    }
}
