<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoPublicacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratopublicacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contratohistorico_id');
            $table->date('data_publicacao')->nullable();
            $table->string('texto_rtf')->nullable();
            $table->string('hash')->nullable();
            $table->string('status')->nullable();
            $table->string('situacao')->nullable();
            $table->timestamps();

            $table->foreign('contratohistorico_id')->references('id')->on('contratohistorico')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratopublicacoes');
    }
}
