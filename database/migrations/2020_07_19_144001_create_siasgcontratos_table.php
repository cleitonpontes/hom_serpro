<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiasgcontratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siasgcontratos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('compra_id')->nullable();
            $table->integer('unidade_id')->nullable();
            $table->integer('tipo_id');
            $table->string('numero');
            $table->string('ano');
            $table->string('codigo_interno')->default('0000000000');
            $table->integer('unidadesubrrogacao_id')->nullable();
            $table->string('mensagem')->nullable();
            $table->string('situacao');
            $table->integer('contrato_id')->nullable();
            $table->json('json')->nullable();
            $table->boolean('sisg')->default(true);
            $table->timestamps();

            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('unidadesubrrogacao_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('compra_id')->references('id')->on('siasgcompras')->onDelete('cascade');
            $table->foreign('tipo_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('set null');

            $table->unique(['unidade_id', 'tipo_id', 'numero', 'ano']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siasgcontratos');
    }
}
