<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfpcoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfpco', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable()->default(0)->change();
            $table->integer('codugempe')->nullable()->default(0)->change();
            $table->integer('numclassd')->nullable()->default(0)->change();
            $table->integer('numclasse')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfpco', function (Blueprint $table) {
            $table->bigInteger('numseqitem')->nullable();
            $table->integer('codugempe')->nullable();
            $table->integer('numclassd')->nullable();
            $table->integer('numclasse')->nullable();
        });
    }
}
