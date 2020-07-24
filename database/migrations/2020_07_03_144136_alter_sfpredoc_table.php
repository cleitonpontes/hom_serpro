<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraSfpredocTable extends Migration
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
        Schema::table('sfpredoc', function (Blueprint $table) {
            $table->dropForeign('sfpredoc_sfded_id_foreign');
            $table->foreign('sfded_id')->references('id')->on('sfdeducao_encargo_dadospagto')->onDelete('cascade');
        });
    }
}
