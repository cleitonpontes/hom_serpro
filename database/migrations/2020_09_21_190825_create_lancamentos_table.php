<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLancamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('contratoterceirizado_id');
            $table->foreign('contratoterceirizado_id')->references('id')->on('contratoterceirizados')->onDelete('cascade');

            $table->integer('encargo_id');
            $table->foreign('encargo_id')->references('id')->on('encargos')->onDelete('cascade');

            $table->decimal('valor',15,2)->nullable();

            $table->integer('movimentacao_id');
            $table->foreign('movimentacao_id')->references('id')->on('movimentacaocontratocontas');

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
        Schema::dropIfExists('lancamentos');
    }
}
