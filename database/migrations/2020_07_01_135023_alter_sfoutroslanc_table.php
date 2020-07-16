<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfoutroslancTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfoutroslanc', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->boolean('indrliquidado')->nullable()->default(0)->change();
            $table->decimal('vlr',15,2)->nullable()->default(0)->change();
            $table->integer('numclassa')->nullable()->default(0)->change();
            $table->integer('numclassb')->nullable()->default(0)->change();
            $table->integer('numclassc')->nullable()->default(0)->change();
            $table->integer('numclassd')->nullable()->default(0)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfoutroslanc', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable();
            $table->decimal('vlr',15,2)->nullable();
            $table->integer('numclassa')->nullable();
            $table->integer('numclassb')->nullable();
            $table->integer('numclassc')->nullable();
            $table->integer('numclassd')->nullable();
        });
    }
}
