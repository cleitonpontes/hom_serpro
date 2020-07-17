<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCpfSfpadrao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfpadrao', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
        });
        Schema::table('sfpadrao', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfpadrao', function (Blueprint $table) {
            $table->dropForeign('sfpadrao_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
