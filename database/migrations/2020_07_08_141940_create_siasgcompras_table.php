<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiasgcomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siasgcompras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unidade_id');
            $table->string('ano');
            $table->string('numero');
            $table->integer('modalidade_id');
            $table->string('mensagem')->nullable();
            $table->string('situacao');
            $table->json('json')->nullable();
            $table->timestamps();

            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('modalidade_id')->references('id')->on('codigoitens')->onDelete('cascade');

            $table->unique(['unidade_id','ano','numero','modalidade_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siasgcompras');
    }
}
