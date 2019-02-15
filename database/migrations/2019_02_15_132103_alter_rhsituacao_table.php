<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRhsituacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rhsituacao', function ($table) {
            $table->dropColumn('nddesc');
            $table->dropColumn('vpddesc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rhsituacao', function (Blueprint $table) {
            $table->integer('nddesc')->nullable();
            $table->integer('vpddesc')->nullable();
        });
    }
}
