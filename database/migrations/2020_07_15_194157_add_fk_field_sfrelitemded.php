<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkFieldSfrelitemded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfrelitemded', function (Blueprint $table) {
            $table->dropForeign('sfrelitemded_sfded_id_foreign');
            $table->integer('sfded_id')->nullable()->change();
            $table->string('tipo')->nullable()->change();
        });

        Schema::table('sfrelitemded', function (Blueprint $table) {
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
        Schema::table('sfrelitemded', function (Blueprint $table) {
            $table->dropForeign('sfrelitemded_sfded_id_foreign');
        });
    }
}
