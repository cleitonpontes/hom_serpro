<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpenhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empenhos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->integer('unidade_id');
            $table->integer('fornecedor_id');
            $table->integer('planointerno_id')->nullable();
            $table->integer('naturezadespesa_id');
            $table->decimal('empenhado',17,2)->nullable();
            $table->decimal('aliquidar',17,2)->nullable();
            $table->decimal('liquidado',17,2)->nullable();
            $table->decimal('pago',17,2)->nullable();
            $table->decimal('rpinscrito',17,2)->nullable();
            $table->decimal('rpaliquidar',17,2)->nullable();
            $table->decimal('rpliquidado',17,2)->nullable();
            $table->decimal('rppago',17,2)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
            $table->foreign('planointerno_id')->references('id')->on('planointerno')->onDelete('cascade');
            $table->foreign('naturezadespesa_id')->references('id')->on('naturezadespesa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empenhos');
    }
}
