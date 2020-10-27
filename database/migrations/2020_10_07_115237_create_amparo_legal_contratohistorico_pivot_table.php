<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmparoLegalContratohistoricoPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amparo_legal_contratohistorico', function (Blueprint $table) {
            $table->integer('amparo_legal_id')->unsigned()->index();
            $table->foreign('amparo_legal_id')->references('id')->on('amparo_legal')->onDelete('cascade');
            $table->integer('contratohistorico_id')->unsigned()->index();
            $table->foreign('contratohistorico_id')->references('id')->on('contratohistorico')->onDelete('cascade');
            $table->primary(['amparo_legal_id', 'contratohistorico_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amparo_legal_contratohistorico');
    }
}
