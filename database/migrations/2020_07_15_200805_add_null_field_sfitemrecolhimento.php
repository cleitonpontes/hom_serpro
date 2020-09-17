<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullFieldSfitemrecolhimento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
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
        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->integer('sfded_id')->change();
        });
    }
}
