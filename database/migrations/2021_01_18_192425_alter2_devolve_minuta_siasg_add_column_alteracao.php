<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alter2DevolveMinutaSiasgAddColumnAlteracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->bigInteger('minutaempenhos_remessa_id')->nullable();
            $table->foreign('minutaempenhos_remessa_id')->references('id')->on('minutaempenhos_remessa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolve_minuta_siasg', function (Blueprint $table) {
            $table->dropColumn('minutaempenhos_remessa_id');
        });
    }
}
