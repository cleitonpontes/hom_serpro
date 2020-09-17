<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSfcompensacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sfcompensacao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sfpadrao_id');
            $table->bigInteger('numseqitem')->nullable();
            $table->string('codsit')->nullable();
            $table->decimal('vlr',15,2)->nullable()->default(0);
            $table->string('txtinscra')->nullable();
            $table->integer('numclassa')->nullable();
        });

        Schema::table('sfcompensacao', function (Blueprint $table) {
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
        Schema::table('sfcompensacao', function (Blueprint $table) {
            //
        });
    }
}
