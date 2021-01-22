<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinutaempenhosRemessaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minutaempenhos_remessa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('minutaempenho_id')->unsigned()->index();
            $table->foreign('minutaempenho_id')->references('id')->on('minutaempenhos')->onDelete('cascade');
            $table->integer('situacao_id')->unsigned()->index();
            $table->foreign('situacao_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->integer('remessa')->default(0);
            $table->string('mensagem_siafi')->nullable(); //gravar retorno do WS

            $table->unique(['minutaempenho_id','remessa']);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minutaempenhos_remessa');
    }
}
