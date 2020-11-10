<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateUnidadeconfiguracaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidadeconfiguracao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unidade_id')->unique();
            $table->integer('user1_id');
            $table->integer('user2_id')->nullable();
            $table->integer('user3_id')->nullable();
            $table->integer('user4_id')->nullable();
            $table->string('telefone1')->nullable();
            $table->string('telefone2')->nullable();
            $table->boolean('email_diario')->default(true);
            $table->string('email_diario_periodicidade')->default('30;60;90;120;180');
            $table->text('email_diario_texto')->nullable();
            $table->boolean('email_mensal')->default(true);
            $table->integer('email_mensal_dia')->default('1');
            $table->text('email_mensal_texto')->nullable();
            $table->timestamps();

            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('user1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user2_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user3_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user4_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidadeconfiguracao');
    }
}
