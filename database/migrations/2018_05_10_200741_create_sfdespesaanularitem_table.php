<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdespesaanularitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdespesaanularitem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfdespesaanular_id');
            $table->bigInteger('numseqitem')->nullable();
            $table->string('numempe')->nullable();
            $table->integer('codsubitemempe')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->string('txtinscra')->nullable();
            $table->integer('numclassa')->nullable();
            $table->string('txtinscrb')->nullable();
            $table->integer('numclassb')->nullable();
            $table->string('txtinscrc')->nullable();
            $table->integer('numclassc')->nullable();
        });

        Schema::table('sfdespesaanularitem', function (Blueprint $table) {
            $table->foreign('sfdespesaanular_id')->references('id')->on('sfdespesaanular')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sfdespesaanularitem');
    }
}
