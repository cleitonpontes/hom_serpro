<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoitemServicoIndicadorPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoitem_servico_indicador', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('contratoitem_servico_id')->unsigned()->index();
            $table->foreign('contratoitem_servico_id')->references('id')->on('contratoitem_servico')->onDelete('cascade');

            $table->integer('indicador_id')->unsigned()->index();
            $table->foreign('indicador_id')->references('id')->on('indicadores')->onDelete('cascade');

            $table->boolean('tipo_afericao')->comment('false - Por Percentual, true - Por número de ocorrencias');

            $table->decimal('vlrmeta',15,2)->nullable()->default(0)->change();
            //TODO FALTA PERIODICIDADE
            $table->timestamps();
//            $table->softDeletes();

            $table->unique(['contratoitem_servico_id', 'indicador_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratoitem_servico_indicador');
    }
}
