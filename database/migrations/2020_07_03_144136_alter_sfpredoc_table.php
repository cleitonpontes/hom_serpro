<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSfpredocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfpredoc', function (Blueprint $table) {
            $table->dropForeign('sfpredoc_sfded_id_foreign');

            $table->integer('sfencargos_id')->nullable();
            $table->integer('sfdadospgto_id')->nullable();
        });

        Schema::table('sfpredoc', function (Blueprint $table) {
            $table->foreign('sfded_id')->references('id')->on('sfdeducao')->onDelete('cascade');
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
            $table->dropForeign('sfpredoc_sfded_id_foreign');
            $table->dropForeign('sfpredoc_sfencargos_id_foreign');
            $table->dropForeign('sfpredoc_sfdadospgto_id_foreign');
            $table->foreign('sfded_id')->references('id')->on('sfdeducao_encargo_dadospagto')->onDelete('cascade');

        });
    }
}
