<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoitensCollumnPeriodicidadeDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->integer('periodicidade')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratoitens', function (Blueprint $table) {
            $table->integer('periodicidade')->nullable()->change();
        });
    }
}
