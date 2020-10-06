<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmparoLegalContratoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amparo_legal_contrato', function (Blueprint $table) {
            $table->integer('amparo_legal_id')->unsigned()->index();
            $table->foreign('amparo_legal_id')->references('id')->on('amparo_legal')->onDelete('cascade');
            $table->integer('contrato_id')->unsigned()->index();
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->primary(['amparo_legal_id', 'contrato_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amparo_legal_contrato');
    }
}
