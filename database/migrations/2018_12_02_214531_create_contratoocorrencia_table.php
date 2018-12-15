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
            $table->integer('contrato_id');
            $table->integer('user_id');
            $table->date('data');
            $table->text('ocorrencia');
            $table->boolean('notificapreposto');
            $table->string('emailpreposto');
            $table->string('situacao');
            $table->softDeletes();
            $table->timestamps();
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
