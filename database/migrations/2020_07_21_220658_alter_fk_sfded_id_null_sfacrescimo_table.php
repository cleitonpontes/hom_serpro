<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFkSfdedIdNullSfacrescimoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfacrescimo', function (Blueprint $table) {
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
            $table->integer('sfded_id')->change();
        });
    }
}
