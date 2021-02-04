<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSforcempenhodadosCollumnSfnonceId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sforcempenhodados', function (Blueprint $table) {
            $table->bigInteger('sfnonce_id')->nullable();
            $table->foreign('sfnonce_id')->references('id')->on('sfnonce');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sforcempenhodados', function (Blueprint $table) {
            $table->dropColumn('sfnonce_id');
        });
    }
}
