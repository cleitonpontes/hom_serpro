<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoocorrenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoocorrencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numero');
            $table->integer('contrato_id');
            $table->integer('user_id');
            $table->date('data');
            $table->text('ocorrencia');
            $table->boolean('notificapreposto');
            $table->string('emailpreposto')->nullable();
            $table->integer('numeroocorrencia')->nullable();
            $table->integer('novasituacao')->nullable();
            $table->string('arquivos')->nullable();
            $table->integer('situacao');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('numeroocorrencia')->references('id')->on('contratoocorrencias')->onDelete('cascade');
            $table->foreign('novasituacao')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('situacao')->references('id')->on('codigoitens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratoocorrencias');
    }
}
