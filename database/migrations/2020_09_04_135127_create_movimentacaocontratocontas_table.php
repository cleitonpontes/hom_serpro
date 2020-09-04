<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimentacaocontratocontasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimentacaocontratocontas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contratoconta_id');
            $table->integer('tipo_id');
            $table->integer('contratoterceirizado_id');
            $table->integer('encargo_id');
            $table->string('mes_competencia');
            $table->string('ano_competencia');
            $table->integer('proporcionalidade');
            $table->decimal('valor',15,2)->nullable();

            $table->foreign('contratoconta_id')->references('id')->on('contratocontas')->onDelete('cascade');
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('contratoterceirizado_id')->references('id')->on('contratoterceirizados')->onDelete('cascade');
            $table->foreign('encargo_id')->references('id')->on('encargos')->onDelete('cascade');

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
        Schema::dropIfExists('movimentacaocontratocontas');
    }
}
