<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApropriacoesFaturasContratofaturasPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apropriacoes_faturas_contratofaturas', function (Blueprint $table) {
            $table->bigInteger('apropriacoes_faturas_id')->unsigned()->index();
            $table->bigInteger('contratofaturas_id')->unsigned()->index();
            $table->foreign('apropriacoes_faturas_id')->references('id')->on('apropriacoes_faturas')->onDelete('cascade');
            $table->foreign('contratofaturas_id')->references('id')->on('contratofaturas')->onDelete('cascade');

            $table->primary(['apropriacoes_faturas_id', 'contratofaturas_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apropriacoes_faturas_contratofaturas');
    }
}
