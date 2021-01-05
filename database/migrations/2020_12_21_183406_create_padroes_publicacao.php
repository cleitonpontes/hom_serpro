<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePadroesPublicacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('padroespublicacao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tipo_contrato_id');
            $table->integer('tipo_mudanca_id');
            $table->text('texto_padrao');
            $table->timestamps();

            $table->unique(['tipo_contrato_id','tipo_mudanca_id']);

            $table->foreign('tipo_contrato_id')->references('id')->on('codigoitens')->onDelete('cascade');
            $table->foreign('tipo_mudanca_id')->references('id')->on('codigoitens')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('padroespublicacao');
    }
}
