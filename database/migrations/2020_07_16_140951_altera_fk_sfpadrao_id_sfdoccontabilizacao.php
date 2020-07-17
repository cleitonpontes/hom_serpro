<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraFkSfpadraoIdSfdoccontabilizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfdoccontabilizacao', function (Blueprint $table) {
            $table->dropColumn('sfpadrao_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdoccontabilizacao', function (Blueprint $table) {
            $table->integer('sfpadrao_id');
        });
    }
}
