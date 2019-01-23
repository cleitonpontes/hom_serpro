<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfitemrecolhimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfitemrecolhimento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfded_id');
            $table->bigInteger('numseqitem')->nullable();
            $table->string('codrecolhedor')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->decimal('vlrbasecalculo',15,2)->nullable();
            $table->decimal('vlrmulta',15,2)->nullable();
            $table->decimal('vlrjuros',15,2)->nullable();
            $table->decimal('vlroutrasent',15,2)->nullable();
            $table->decimal('vlratmmultajuros',15,2)->nullable();
        });

        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao_encargo_dadospagto')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfitemrecolhimento');
    }
}
