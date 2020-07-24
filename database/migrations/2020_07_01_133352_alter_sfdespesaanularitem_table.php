<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfdespesaanularitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfdespesaanularitem', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->integer('codsubitemempe')->nullable()->default(0)->change();
            $table->decimal('vlr',15,2)->nullable()->default(0)->change();
            $table->integer('numclassa')->nullable()->default(0)->change();
            $table->integer('numclassb')->nullable()->default(0)->change();
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
        Schema::table('sfdespesaanularitem', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable();
            $table->integer('codsubitemempe')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->integer('numclassa')->nullable();
            $table->integer('numclassb')->nullable();
            $table->integer('numclassc')->nullable();
        });
    }
}
