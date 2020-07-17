<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkSfpadraoIdSfdadospgto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfdadospgto', function (Blueprint $table) {
            $table->integer('sfpadrao_id')->nullable();
        });

        Schema::table('sfdadospgto', function (Blueprint $table) {
            $table->foreign('sfpadrao_id')->references('id')->on('sfpadrao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfdadospgto', function (Blueprint $table) {
            $table->dropForeign('sfdadospgto_sfpadrao_id_foreign');
            $table->dropColumn('sfpadrao_id');
        });
    }
}
