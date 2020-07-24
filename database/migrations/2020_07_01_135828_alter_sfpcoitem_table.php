<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfpcoitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfpcoitem', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->string('numempe')->nullable()->change();
            $table->integer('codsubitemempe')->nullable()->default(0)->change();
            $table->boolean('indrliquidado')->nullable()->change();
            $table->decimal('vlr',15,2)->nullable()->default(0)->change();
            $table->string('txtinscra')->nullable()->change();
            $table->integer('numclassa')->nullable()->default(0)->change();
            $table->string('txtinscrb')->nullable()->change();
            $table->integer('numclassb')->nullable()->default(0)->change();
            $table->string('txtinscrc')->nullable()->change();
            $table->integer('numclassc')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfpcoitem', function (Blueprint $table) {
            $table->bigInteger('numseqitem');
            $table->string('numempe');
            $table->integer('codsubitemempe');
            $table->boolean('indrliquidado');
            $table->decimal('vlr',15,2);
            $table->string('txtinscra');
            $table->integer('numclassa');
            $table->string('txtinscrb');
            $table->integer('numclassb');
            $table->string('txtinscrc');
            $table->integer('numclassc');
        });
    }
}
