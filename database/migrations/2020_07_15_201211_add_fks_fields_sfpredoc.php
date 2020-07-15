<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFksFieldsSfpredoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfpredoc', function (Blueprint $table) {

            $table->integer('sfded_id')->nullable()->change();
            $table->integer('sfencargos_id')->nullable()->after('sfded_id');
            $table->integer('sfdadospgto_id')->nullable()->after('sfencargos_id');
        });

        Schema::table('sfpredoc', function (Blueprint $table) {
            $table->foreign('sfencargos_id')->references('id')->on('sfencargo')->onDelete('cascade');
            $table->foreign('sfdadospgto_id')->references('id')->on('sfdadospgto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfpredoc', function (Blueprint $table) {
            $table->dropForeign('sfpredoc_sfencargos_id_foreign');
            $table->dropForeign('sfpredoc_sfdadospgto_id_id_foreign');
        });
    }
}
