<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfdeducaoEncargoDadospagtoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfdeducao_encargo_dadospagto', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->integer('codugpgto')->nullable()->default(0)->change();
            $table->decimal('vlr',15,2)->nullable()->default(0)->change();
            $table->integer('numclassa')->nullable()->default(0)->change();
            $table->integer('numclassb')->nullable()->default(0)->change();
            $table->integer('numclassc')->nullable()->default(0)->change();
            $table->integer('numclassd')->nullable()->default(0)->change();
            $table->integer('codugempe')->nullable()->default(0)->change();
            $table->integer('codsubitemempe')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdeducao_encargo_dadospagto', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable();
            $table->integer('codugpgto')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->integer('numclassa')->nullable();
            $table->integer('numclassb')->nullable();
            $table->integer('numclassc')->nullable();
            $table->integer('numclassd')->nullable();
            $table->integer('codugempe')->nullable();
            $table->integer('codsubitemempe')->nullable();
        });
    }
}
