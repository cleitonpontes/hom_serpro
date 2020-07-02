<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfdeducaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfdeducao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpadrao_id');
            $table->bigInteger('numseqitem')->nullable();
            $table->string('codsit')->nullable();
            $table->date('dtvenc')->nullable();
            $table->date('dtpgtoreceb')->nullable();
            $table->integer('codugpgto')->nullable();
            $table->decimal('vlr',15,2)->nullable()->default(0);
            $table->boolean('indrliquidado')->nullable()->default(true);
            $table->string('txtinscra')->nullable();
            $table->integer('numclassa')->nullable();
            $table->string('txtinscrb')->nullable();
            $table->integer('numclassb')->nullable();
            $table->string('txtinscrc')->nullable();
            $table->integer('numclassc')->nullable();
            $table->string('txtinscrd')->nullable();
            $table->integer('numclassd')->nullable();

        });
        Schema::table('sfdeducao', function (Blueprint $table) {
            $table->foreign('sfpadrao_id')->references('id')->on('sfpadrao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdeducao', function (Blueprint $table) {
            //
        });
    }
}
