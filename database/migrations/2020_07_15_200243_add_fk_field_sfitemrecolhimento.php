<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkFieldSfitemrecolhimento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->dropForeign('sfitemrecolhimento_sfded_id_foreign');
        });

        Schema::table('sfitemrecolhimento', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao')->onDelete('cascade');
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
            $table->dropForeign('sfitemrecolhimento_sfded_id_foreign');
        });
    }
}
