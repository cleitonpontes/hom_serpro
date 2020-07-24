<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraSfacrescimoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfacrescimo', function (Blueprint $table) {
            $table->dropForeign('sfacrescimo_sfded_id_foreign');
            $table->integer('sfded_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfacrescimo', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao')->onDelete('cascade');
        });
    }
}
