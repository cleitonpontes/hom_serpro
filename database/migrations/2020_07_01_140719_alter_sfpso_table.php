<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfpsoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfpso', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->integer('numclasse')->nullable()->default(0)->change();
            $table->integer('numclassf')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfpso', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable();
            $table->integer('numclasse')->nullable();
            $table->integer('numclassf')->nullable();
        });
    }
}
