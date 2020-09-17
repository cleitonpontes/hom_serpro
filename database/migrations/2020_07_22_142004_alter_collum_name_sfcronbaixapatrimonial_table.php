<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollumNameSfcronbaixapatrimonialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sfcronbaixapatrimonial', function (Blueprint $table) {
            $table->renameColumn('outroslanc_id','sfoutroslanc_id');
            $table->dropForeign('sfcronbaixapatrimonial_outroslanc_id_foreign');
            $table->foreign('sfoutroslanc_id')->references('id')->on('sfoutroslanc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sfcronbaixapatrimonial', function (Blueprint $table) {
            $table->renameColumn('sfoutroslanc_id','outroslanc_id');
            $table->dropForeign('sfcronbaixapatrimonial_sfoutroslanc_id_foreign');
            $table->foreign('outroslanc_id')->references('id')->on('sfoutroslanc')->onDelete('cascade');
        });
    }
}
