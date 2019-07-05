<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComunicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comunica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unidade_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('assunto');
            $table->text('mensagem');
            $table->string('anexos')->nullable();
            $table->char('situacao',1)->default('P');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comunica');
    }
}
