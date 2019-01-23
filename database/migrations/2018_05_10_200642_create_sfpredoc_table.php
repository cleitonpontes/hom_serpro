<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfpredocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfpredoc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfded_id');
            $table->string('txtobser')->nullable();
            $table->string('codrecurso')->nullable();
            $table->date('dtprdoapuracao')->nullable();
            $table->string('numref')->nullable();
            $table->string('txtprocesso')->nullable();
            $table->decimal('vlrrctabrutaacum',15,2)->nullable();
            $table->decimal('vlrpercentual',15,2)->nullable();
            $table->string('mesreferencia')->nullable();
            $table->string('anoreferencia')->nullable();
            $table->integer('codugtmdrserv')->nullable();
            $table->integer('numnf')->nullable();
            $table->string('txtserienf')->nullable();
            $table->integer('numsubserienf')->nullable();
            $table->integer('codmuninf')->nullable();
            $table->date('dtemisnf')->nullable();
            $table->decimal('vlrnf',15,2)->nullable();
            $table->decimal('numaliqnf',15,2)->nullable();
            $table->string('numcodbarras')->nullable();
            $table->integer('codugfavorecida')->nullable();
            $table->string('codrecolhedor')->nullable();
            $table->integer('numreferencia')->nullable();
            $table->string('mescompet')->nullable();
            $table->string('anocompet')->nullable();
            $table->decimal('vlrdocumento',15,2)->nullable();
            $table->decimal('vlrdesconto',15,2)->nullable();
            $table->decimal('vlroutrdeduc',15,2)->nullable();
            $table->integer('codrecolhimento')->nullable();
            $table->boolean('indradiant13')->nullable();
            $table->string('codtipoob')->nullable();
            $table->string('codcredordevedor')->nullable();
            $table->string('codnumlista')->nullable();
            $table->string('txtcit')->nullable();
            $table->string('tipo');
        });

        Schema::table('sfpredoc', function (Blueprint $table) {
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
        Schema::dropIfExists('sfpredoc');
    }
}
