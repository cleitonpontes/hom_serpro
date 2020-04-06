<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrgaoToComunica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comunica', function (Blueprint $table) {
            $table->integer('orgao_id')->nullable()->default('')->after('id');;

            $table->foreign('orgao_id')->references('id')->on('orgaos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comunica', function (Blueprint $table) {
            $table->dropColumn(['orgao_id']);
        });
    }
}
