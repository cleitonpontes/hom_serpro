<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgaos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orgaosuperior_id')->unsigned();
            $table->string('codigo')->unique();
            $table->string('codigosiasg')->nullable();
            $table->string('nome');
            $table->string('situacao');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('orgaosuperior_id')->references('id')->on('orgaosuperiors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orgaos');
    }
}
