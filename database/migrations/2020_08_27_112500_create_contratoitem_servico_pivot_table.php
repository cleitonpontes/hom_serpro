<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoitemServicoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratoitem_servico', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('contratoitem_id')->unsigned()->index();
            $table->foreign('contratoitem_id')->references('id')->on('contratoitens')->onDelete('cascade');
            $table->integer('servico_id')->unsigned()->index();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');

            $table->timestamps();
//            $table->softDeletes();

            $table->unique(['contratoitem_id', 'servico_id']);
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
